<?php

namespace App\Services;

use App\Models\Transaction;


class BankService 
{
    public function deposit($accountToDeposit, $amount)
    {
        $transaction = new Transaction();

        $transaction->cash_from = $accountToDeposit->email;
        $transaction->cash_to = $accountToDeposit->email;
        $transaction->amount = $amount;
        $transaction->type = 'CREDIT';

        $accountToDeposit->setBalance($amount);
        $accountToDeposit->save();
        
        $transaction->status = 'COMPLETED';
        $transaction->save();

    }

    public function withdrawal($accountToWithdraw, $amount)
    {
        $transaction = new Transaction();

        $transaction->cash_from = $accountToWithdraw->email;
        $transaction->cash_to = $accountToWithdraw->email;
        $transaction->amount = $amount;
        $transaction->type = 'DEBIT';

        $accountToWithdraw->setBalance(-$amount);
        $accountToWithdraw->save();

        $transaction->status = 'COMPLETED';
        $transaction->save();

    }

    public function transfer($accountToWithdraw, $accountToDeposit, $amount)
    {
        $transaction = new Transaction();

        $transaction->cash_from = $accountToWithdraw->email;
        $transaction->cash_to = $accountToDeposit->email;
        $transaction->amount = $amount;
        $transaction->type = 'TRANSFER';

        $accountToDeposit->setBalance($amount);
        $accountToWithdraw->setBalance(-$amount);

        $accountToWithdraw->save();
        $accountToDeposit->save();

        $transaction->status = 'COMPLETED';
        $transaction->save();

    }
}

