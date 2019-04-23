<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RewardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => (int)$this->id,
            'rewardable_type' => (string)$this->rewardable_type,
            'rewardable_id' => (int)$this->rewardable_id,
            'attribure' => (string)$this->attribute,
            'value' => (int)$this->value,
            'created_at' => (string)$this->created_at,
            'user_id' => (int)$this->user_id,
        ];
    }
}
