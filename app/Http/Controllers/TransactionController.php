<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Http\Requests\TransferRequest;
use App\Models\Transaction;
use App\Models\User;
use App\Services\BankService;
use Faker\Core\Number;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    private $bankService;

    public function __construct(BankService $bankService)
    {
        $this->bankService = $bankService;
    }


    public function dashboard(Request $request)
    {

        /*
        $transactions = Transaction::where('cash_to', Auth::user()->email)
            ->orWhere('cash_from', Auth::user()->email)
            ->oldest()
            ->get();

        $balance = 0;
        foreach($transactions as $transaction) {
            $transaction->type() == "CREDIT" ? 
                            $balance += $transaction->amount : 
                            $balance -= $transaction->amount;
            $transaction['balance'] = $balance; 
        }
        */

        $transactions = Transaction::select(
            '*',
            DB::raw('SUM(
                CASE 
                    WHEN type = "TRANSFER" AND (cash_to = ?) THEN amount AND (cash_from = ?) THEN -amount
                    WHEN type = "CREDIT" THEN amount 
                    WHEN type = "DEBIT" THEN -amount 
                    ELSE 0 
                END
            ) OVER (ORDER BY created_at) as balance'),
        )
        ->addBinding(Auth::user()->email, 'select')
        ->addBinding(Auth::user()->email, 'select')
        ->where(function ($query) {
            $userEmail = Auth::user()->email;
            $query->where('cash_to', $userEmail)->orWhere('cash_from', $userEmail);
        })
        ->oldest()
        ->paginate(5);
        
        return view('dashboard', compact('transactions'));
    }

    public function index() 
    {
        return view('transactions');
    }
    
    public function doCashDeposit(TransactionRequest $request) 
    {
        try {

            $accountToDeposit = User::find(Auth::user()->id);
            $this->bankService->deposit($accountToDeposit, $request->amount);

            return redirect()->route('transactions')->with(['success'=>'Transaction Completed']);
        } catch (\Throwable $th) {
            return redirect()->route('transactions')->with(['error'=>'Transaction Failed']);
        }
    }

    public function doCashWithdrawal(TransactionRequest $request) 
    {

        try {
            
            $accountToWithdraw = User::find(Auth::user()->id);
            if($accountToWithdraw->balance <  $request->amount) {
                return redirect()->route('transactions')->with(['error'=>'Transaction Failed. No Sufficient Balance for Withdrawal.']);
            }
            
            $this->bankService->withdrawal($accountToWithdraw, $request->amount);

            return redirect()->route('transactions')->with(['success'=>'Transaction Completed']);
        } catch (\Throwable $th) {
            return redirect()->route('transactions')->with(['error'=>'Transaction Failed']);
        }
        
    }

    public function doCashTransfer(TransferRequest $request) 
    {

        try {

            $accountToWithdraw = User::find(Auth::user()->id);
            $accountToDeposit = User::where('email', $request->email)->first();
            
            if($accountToDeposit == NULL){
                return redirect()->route('transactions')->with(['error'=>'Transaction Failed. No User with Given Email Found.']);
            }

            if($accountToWithdraw->balance <  $request->amount) {
                return redirect()->route('transactions')->with(['error'=>'Transaction Failed. No Sufficient Balance for Transfer.']);
            }

            $this->bankService->transfer($accountToWithdraw, $accountToDeposit, $request->amount);

            return redirect()->route('transactions')->with(['success'=>'Transaction Completed']);
        } catch (\Throwable $th) {
            return redirect()->route('transactions')->with(['error'=>'Transaction Failed']);
        }
        
    }
}
