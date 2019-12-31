<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CollectionResource extends JsonResource
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
            'type' => 'collection',
            'id' => (int)$this->id,
            'attributes' => [
                'user_id' =>  (int)$this->user_id,
                'thread_id' => (int)$this->thread_id,
                'keep_updated' => (bool)$this->keep_updated,
                'is_updated' => (bool)$this->is_updated,
            ],
        ];
    }
}
