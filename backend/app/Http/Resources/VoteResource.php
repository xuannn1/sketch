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
    public function toArray($request)
    {
        $author = [];
        $receiver = [];
        if ($this->showUser($this)){
            $author = new UserBriefResource($this->whenLoaded('author'));
            $receiver = new UserBriefResource($this->whenLoaded('receiver'));
        }
        return [
            'type' => 'vote',
            'id' => (int)$this->id,
            'attributes' => [
                'votable_type' => (string)$this->votable_type,
                'votable_id' => (int)$this->votable_id,
                'attitude' => (string)$this->attitude,
                'created_at' => (string)$this->created_at,
            ],
            'author' => $author,
            'receiver' => $receiver,
        ];
    }

    private function isUpvote($attitude){
        return $attitude === 'upvote';
    }

    private function isOwnVote($user_id){
        return auth('api')->id()===$user_id;
    }

    private function showUser($vote){
        return $this->isUpvote($vote->attitude)||$this->isOwnVote($vote->user_id)||auth('api')->user()->isAdmin();
    }

}
