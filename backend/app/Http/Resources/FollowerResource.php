<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;

class FollowerResource extends JsonResource
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
            'id'=>(int)$this->user_id,
            'keep_notified'=>(boolean)$this->keep_notified,
            'is_notified'=>(boolean)$this->is_notified,
        ];
    }
}
