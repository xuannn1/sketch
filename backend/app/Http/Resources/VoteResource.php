<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VoteResource extends JsonResource
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
            'votable_type' => (string)$this->votable_type,
            'votable_id' => (int)$this->votable_id,
            'attitude' => (string)$this->attitude,
            'created_at' => (string)$this->created_at,
        ];
    }
}
