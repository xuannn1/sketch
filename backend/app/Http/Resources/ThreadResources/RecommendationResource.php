<?php

namespace App\Http\Resources\ThreadResources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\AuthorIdentifierResource;

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
            'id' => $this->id,
            'attributes' => [
                'brief' => $this->brief,
                'body' => $this->body,
                'type' => $this->type,
                'created_at' => (string) $this->created_at,
            ],
            'authors' => AuthorIdentifierResource::collection($this->authors),
        ];
    }

}
