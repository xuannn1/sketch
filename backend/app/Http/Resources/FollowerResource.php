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
            'type' => 'follow',
            'attributes' => [
                'keep_updated'=>(boolean)$this->pivot->keep_updated,
                'is_updated'=>(boolean)$this->pivot->is_updated,
            ],
            'user' => new UserBriefResource($this),

        ];
    }
}
