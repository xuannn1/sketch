<?php

namespace App\Http\Resources;

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

        $intro = [];
        if($this['intro']) {
            $intro = new UserIntroResource($this['intro']);
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
            'info' => new UserInfoResource($info),
            'intro' => $intro,
        ];
    }
}
