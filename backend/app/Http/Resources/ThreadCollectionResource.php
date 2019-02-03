<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ThreadCollectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if (!$this->is_anonymous){
            $author = new AuthorIdentifierResource($this->author);
        }else{
            $author = [];
        }
        return [
            'type' => 'thread',
            'id' => (int)$this->id,
            'attributes' => [
                'title' => (string)$this->title,
                'is_anonymous' => (bool)$this->is_anonymous,
                'majia' => (string)$this->majia,
            ],
            'author' => $author,
            'channel'        => new ChannelBriefResource($this->channel()),
            'tags' => TagInfoResource::collection($this->tags),
            'last_component' => new PostBriefResource($this->last_component),
            'last_post' => new PostBriefResource($this->last_post),
            'collection' => [
                'type' => 'collection',
                'id' => (int)$this->collection_id,
                'attributes' => [
                    'keep_updated' => (bool)$this->keep_updated,
                    'is_updated' => (bool)$this->is_updated,
                ],
            ],
        ];
    }
}
