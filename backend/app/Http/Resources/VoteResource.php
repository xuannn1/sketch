<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class VoteResource extends JsonResource
{
    /**
    * Transform the resource into an array.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return array
    */
    protected $isAdmin;

    public function isAdmin($isAdmin){
        $this->isAdmin = $isAdmin;
        return $this;
    }
    public function toArray($request)
    {
        if ($this->showUser($this)){
            $author = new AuthorIdentifierResource($this->whenLoaded('author'));
        }else{
            $author = [];
        }
        return [
            'votable_type' => (string)$this->votable_type,
            'votable_id' => (int)$this->votable_id,
            'attitude' => (string)$this->attitude,
            'created_at' => (string)$this->created_at,
            'author' => $author,
        ];
    }

    private function isUpvote($attitude){
        return $attitude === 'upvote';
    }

    private function isOwnVote($user_id){
        return auth('api')->id()===$user_id;
    }

    private function showUser($vote){
        return $this->isUpvote($vote->attitude)||$this->isOwnVote($vote->user_id)||$this->isAdmin;
    }

}
