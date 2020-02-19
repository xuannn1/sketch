<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class UserInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $private_info = [];
        if (auth('api')->check() && (auth('api')->user()->isAdmin() || auth('api')->id() == $this->user_id)) {
            $private_info = [
                'qiandao_continued' => (int)$this->qiandao_continued,
                'qiandao_all' => (int)$this->qiandao_all,
                'qiandao_at' => Carbon::parse($this->qiandao_at)->diffForHumans(),
                'register_at' => Carbon::parse($this->user->created_at)->diffForHumans(),
                'invitor_id' => (int)$this->invitor_id,
                'token_limit' => (int)$this->token_limit,
                'donation_level' => (int)$this->donation_level,
                'qiandao_reward_limit' => (int)$this->qiandao_reward_limit,
            ];
        }

        return [
            'type' => 'user_info',
            'id' => (int)$this->user_id,
            'attributes' => [
                'salt' => (int)$this->salt,
                'fish' => (int)$this->fish,
                'ham' => (int)$this->ham,
                'follower_count' => (int)$this->follower_count,
                'following_count' => (int)$this->following_count,
                'qiandao_max' => (int)$this->qiandao_max,
                'private_info' => $private_info,
            ],
        ];
    }
}
