<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Transaction extends Model
{
    use HasFactory;

    public function getAmountFormattedAttribute()
    {
        return number_format($this->amount, 2);
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i A');
    }

    public function type()
    {
        $currentUserEmail = Auth::user()->email; 
        return $this->type == "TRANSFER" ? 
        (
            $this->cash_from == $currentUserEmail ? "DEBIT" :  "CREDIT"
        ) : 
        (
            $this->type
        ) ;    
    }

    public function details()
    {
        $currentUserEmail = Auth::user()->email; 
        return $this->type == "TRANSFER" ? 
                (
                    $this->cash_from == $currentUserEmail ? "Transfered to ". $this->cash_to :  "Transfered from ". $this->cash_from
                ) : 
                (
                    $this->type == "CREDIT" ? "Deposit" : "Withdrawal"
                ) ;
    }
}
