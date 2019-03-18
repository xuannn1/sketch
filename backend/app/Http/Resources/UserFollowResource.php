<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserFollowResource extends JsonResource
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
            'type' => 'user',
            'id' => (int)$this->id,
            'attributes' => [
                'name' => (string)$this->name,
            ],
            'title' => new TitleBriefResource($this->whenLoaded('mainTitle')),
            'followInfo' => [
                'keep_updated'=>(boolean)$this->pivot->keep_updated,
                'is_updated'=>(boolean)$this->pivot->is_updated,
            ],
        ];
    }
}
