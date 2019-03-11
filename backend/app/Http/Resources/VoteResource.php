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
        return [
            'id' => (int) $this->id,
            'votable_type' => (string)$this->votable_type,
            'votable_id' => (int)$this->votable_id,
            'attitude' => (string)$this->attitude,
            'created_at' => (string)$this->created_at,
            $this->mergeWhen($this->isAdmin(auth('api')->user())||$this->isUpvote($this->attitude), 
                ['user_id' => (int) $this->user_id,]
            ),//在用户是管理员或者投票内容是upvote的情况下附加user_id
        ];
    }

    private function isAdmin($user){
        return $user->roles()->where('role','admin')->isNotEmpty();
    }

    private function isUpvote($attitude){
        return $attitude == 'upvote';
    }
}
