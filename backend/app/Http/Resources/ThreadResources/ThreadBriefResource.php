<?php

namespace App\Http\Resources\ThreadResources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\AuthorIdentifierResource;

class ThreadBriefResource extends JsonResource
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
            'tags' => TagResource::collection($this->tags)
        ];
    }
}
