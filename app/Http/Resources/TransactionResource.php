<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'transaction_from' => $this->cash_from,
            'transaction_to' => $this->cash_to,
            'amount' => $this->amount,
            'type' => $this->type,
            'status' => $this->status,
            'transaction_timestamp' => $this->created_at,
        ];
    }
}
