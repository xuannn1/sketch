<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserBriefResource extends JsonResource
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
        ];
    }
}
