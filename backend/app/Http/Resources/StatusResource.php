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
            'id' => $this->id,
            'attributes' => [
                'body' => $this->body,
                'created_at' => $this->created_at ? $this->created_at->toDateTimeString() : '',
            ],
            'author' => new AuthorIdentifierResource($this->author),
        ];
    }
}
