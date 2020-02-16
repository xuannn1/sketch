<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = $this['user'];
        $info = $this['info'];

        if($this['intro']) {
            $intro = [
                'type' => 'user_intro',
                'id' => (int)$this['intro']->user_id,
                'attributes' => [
                    'body' => (string)$this['intro']->body,
                ],
            ];
        } else {
            $intro = null;
        }

        if (auth('api')->check() && (auth('api')->user()->isAdmin() || auth('api')->id() === $user->id)) {
            $private_info = [
                'qiandao_continued' => (int)$info->qiandao_continued,
                'qiandao_all' => (int)$info->qiandao_all,
                'qiandao_at' => Carbon::parse($info->qiandao_at)->diffForHumans(),
                'register_at' => Carbon::parse($user->created_at)->diffForHumans(),
                'invitor_id' => (int)$info->invitor_id,
                'token_limit' => (int)$info->token_limit,
                'donation_level' => (int)$info->donation_level,
                'qiandao_reward_limit' => (int)$info->qiandao_reward_limit,
            ];
        } else {
            $private_info = null;
        }

        return [
            'type' => 'user',
            'id' => (int)$user->id,
            'attributes' => [
                'name' => (string)$user->name,
                'acivated' => (boolean)$user->activated,
                'level' => (int)$user->level,
                'title_id' => (int)$user->title_id,
                'role' => (string)$user->role,
                'quiz_level' => (int)$user->quiz_level,
                'no_logging' => (boolean)$user->no_logging,
                'no_posting' => (boolean)$user->no_posting,
                'no_ads' => (boolean)$user->no_ads,
                'no_homework' => (boolean)$user->no_posting,
            ],
            'title' => new TitleBriefResource($user->title),
            'info' => [
                'type' => 'user_info',
                'id' => (int)$info->user_id,
                'attributes' => [
                    'salt' => (int)$info->salt,
                    'fish' => (int)$info->fish,
                    'ham' => (int)$info->ham,
                    'follower_count' => (int)$info->follower_count,
                    'following_count' => (int)$info->following_count,
                    'qiandao_max' => (int)$info->qiandao_max,
                    'private_info' => $private_info,
                ],
            ],
            'intro' => $intro,
        ];
    }
}
