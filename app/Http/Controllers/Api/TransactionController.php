<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends ApiResponseController
{
    public function getTransferData()
    {
        try {

            $transferData = Transaction::where('type', 'TRANSFER')
            ->where(function ($query) {
                $query->where('to', Auth::user()->email)
                      ->orWhere('from', Auth::user()->email);
            })
            ->get();

            return $this->sendResponse(TransactionResource::collection($transferData), "Success");
        
        } catch (\Throwable $th) {
            return $this->sendError("Something Went Wrong.", 500);
        }
    }
}
