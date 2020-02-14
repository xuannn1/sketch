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
        $user = $this['user'];
        $info = $this['info'];
        $intro = $this['intro'];

        if ($user->id == auth('api')->check() && auth('api')->id()) {
            $detail = [
                'qiandao_continued' => $info->qiandao_continued,
                'qiandao_all' => $info->qiandao_all,
                'qiandao_at' => Carbon::parse($info->qiandao_at)->diffForHumans(),
                'register_at' => Carbon::parse($user->created_at)->diffForHumans(),
            ];
        } else {
            $detail = null;
        }

        return [
            'type' => 'userInfo',
            'id' => (int)$info->id,
            'attributes' => [
                'user_brief' => new UserBriefResource($user),
                'activated' => (boolean)$info->activated,
                'no_ads' => (boolean)$info->no_ads,
                'follower' => count($user->followers),
                'following' => count($user->followings),
                'salt' => (int)$info->salt,
                'fish' => (int)$info->fish,
                'ham' => (int)$info->ham,
                'quiz_level' => (int)$user->quiz_level,
                'qiandao_max' =>(int)$info->qiandao_max,
                'intro' => (string)$intro->body,
                'qiandao_detail' => $detail,
            ],
        ];
    }
}
