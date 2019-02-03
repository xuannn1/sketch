<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RecommendationResource extends JsonResource
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
            'type' => 'recommendation',
            'id' => (int)$this->id,
            'attributes' => [
                'brief' => (string)$this->brief,
                'body' => (string)$this->body,
                'type' => (string)$this->type,
                'created_at' => (string) $this->created_at,
            ],
            'authors' => AuthorIdentifierResource::collection($this->authors),
        ];
    }

}
