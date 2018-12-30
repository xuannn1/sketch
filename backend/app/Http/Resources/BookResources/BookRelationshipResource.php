<?php

namespace App\Http\Resources\BookResources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\AuthorIdentifierResource;
use App\Http\Resources\ThreadResources\ThreadTagsRelationshipResource;
use Auth;
class BookRelationshipResource extends JsonResource
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
            'author'         =>  $author,
            'channel'        => [
                'type'              => 'channel',
                'id'                => $this->channel_id,
                'attributes'        => $this->simpleChannel(),
            ],
            'tags'          => (new ThreadTagsRelationshipResource($this->tags))->additional(['thread' => $this]),
        ];
    }
}
