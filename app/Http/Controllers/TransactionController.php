<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Http\Requests\TransferRequest;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{


    public function dashboard()
    {
        $transactions = Transaction::where('from', Auth::user()->email)
        ->orWhere('to', Auth::user()->email)
        ->latest()
        ->get();
        return view('dashboard', compact('transactions'));
    }

    public function index() 
    {
        return view('transactions');
    }
    
    public function doCashDeposit(TransactionRequest $request) 
    {

        try {
            $transaction = new Transaction();
            $user = User::find(Auth::user()->id);

            $transaction->from = Auth::user()->email;
            $transaction->to = Auth::user()->email;
            $transaction->amount = $request->amount;
            $transaction->type = 'DEPOSIT';
            $user->account_balance = $user->account_balance + $request->amount;
            $transaction->status = 'COMPLETED';

            $user->save();
            $transaction->save();

            return redirect()->route('transactions')->with(['success'=>'Transaction Completed']);
        } catch (\Throwable $th) {
            return redirect()->route('transactions')->with(['error'=>'Transaction Failed']);
        }
        
    }

    public function doCashWithdrawal(TransactionRequest $request) 
    {

        try {
            $transaction = new Transaction();
            $user = User::find(Auth::user()->id);

            $transaction->from = Auth::user()->email;
            $transaction->to = Auth::user()->email;
            $transaction->amount = $request->amount;
            $transaction->type = 'WITHDRAWAL';

            if(Auth::user()->account_balance <  $request->amount) {
                $transaction->status = 'FAILED';
                $transaction->save();
                return redirect()->route('transactions')->with(['error'=>'Transaction Failed. No Sufficient Balance for Withdrawal.']);
            }
            
            $user->account_balance = ($user->account_balance - $request->amount);
            $user->save();
            
            $transaction->status = 'COMPLETED';
            $transaction->save();

            return redirect()->route('transactions')->with(['success'=>'Transaction Completed']);
        } catch (\Throwable $th) {
            return redirect()->route('transactions')->with(['error'=>'Transaction Failed']);
        }
        
    }

    public function doCashTransfer(TransferRequest $request) 
    {

        try {
            $transaction = new Transaction();
            $userFrom = User::find(Auth::user()->id);
            $userTo = User::where('email', $request->email)->first();
            
            if($userTo == NULL){
                return redirect()->route('transactions')->with(['error'=>'Transaction Failed. No User with Given Email Found.']);
            }

            $transaction->from = Auth::user()->email;
            $transaction->to = $request->email;
            $transaction->amount = $request->amount;
            $transaction->type = 'TRANSFER';

            if(Auth::user()->account_balance <  $request->amount) {
                $transaction->status = 'FAILED';
                $transaction->save();
                return redirect()->route('transactions')->with(['error'=>'Transaction Failed. No Sufficient Balance for Transfer.']);
            }

            $userFrom->account_balance = ($userFrom->account_balance - $request->amount);
            $userFrom->save();

            $userTo->account_balance = ($userTo->account_balance + $request->amount);
            $userTo->save();

            $transaction->status = 'COMPLETED';
            $transaction->save();

            return redirect()->route('transactions')->with(['success'=>'Transaction Completed']);
        } catch (\Throwable $th) {
            return redirect()->route('transactions')->with(['error'=>'Transaction Failed']);
        }
        
    }
}
