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
            'reward_type' => (string)$this->reward_type,
            'reward_value' => (int)$this->reward_type,
            'created_at' => (string)$this->created_at,
            'deleted_at' => (string)$this->deleted_at,
            'user_id' => (int)$this->user_id,
            'receiver_id' => (int)$this->receiver_id,
        ];
    }
}
