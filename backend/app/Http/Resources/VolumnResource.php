<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VolumnResource extends JsonResource
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
            'type' => 'volumn',
            'id' => (int)$this->id,
            'attributes' => [
                'title' => (string)$this->title,
                'brief' => (string)$this->brief,
                'body' => (string)$this->body,
            ]
        ];
    }
}
