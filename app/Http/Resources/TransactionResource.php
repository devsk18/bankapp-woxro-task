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
            'transaction_from' => $this->from,
            'transaction_to' => $this->to,
            'amount' => $this->amount,
            'type' => $this->type,
            'status' => $this->status,
            'created_at' => $this->created_at->format('d-m-Y H:i A'),
            'updated_at' => $this->updated_at->format('d-m-Y H:i A')
        ];
    }
}
