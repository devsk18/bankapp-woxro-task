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
        $currentUserEmail = Auth::user()->email;
        $transactions = Transaction::select([
            '*',
            DB::raw('(
                SELECT SUM(
                    CASE
                        WHEN type = "CREDIT" OR (type = "TRANSFER" AND cash_to = ?) THEN amount
                        WHEN type = "DEBIT" OR (type = "TRANSFER" AND cash_from = ?) THEN -amount
                        ELSE 0
                    END
                ) 
                FROM transactions AS t2 
                WHERE (t2.created_at <= transactions.created_at) 
                AND (cash_from = ? OR cash_to = ?)
            ) AS balance')
        ])
        ->addBinding($currentUserEmail, 'select')
        ->addBinding($currentUserEmail, 'select')
        ->addBinding($currentUserEmail, 'select')
        ->addBinding($currentUserEmail, 'select')
        ->where(function ($query) use ($currentUserEmail) {
            $query->where('cash_to', $currentUserEmail)
                ->orWhere('cash_from', $currentUserEmail);
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
