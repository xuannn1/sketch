<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StatusResource extends JsonResource
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
            'type' => 'Status',
            'id' => (int)$this->id,
            'attributes' => [
                'body' => (string)$this->body,
                'created_at' => (string)$this->created_at,
            ],
            'author' => new UserBriefResource($this->whenLoaded('author')),
        ];
    }
}
