<?php

namespace App\Http\Resources\BookResources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BookTagsRelationshipResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => TagResource::collection($this->collection),
        ];
    }
}
