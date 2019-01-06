<?php

namespace App\Http\Resources\ThreadResources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\AuthorIdentifierResource;

class ChapterInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $chapter = $this->chapter;
        return [
            'type' => 'chapter',
            'id' => (int)$this->id,
            'attributes' => [
                'title' => (string)$chapter->title,
                'brief' => (string)$chapter->brief,
                'volumn_id' => (int)$chapter->volumn_id,
                'views' => (int)$chapter->views,
                'characters' => (int)$chapter->characters,

                'is_anonymous' => (bool)$this->is_anonymous,
                'majia' => (string)$this->majia ?? '匿名咸鱼',
                'created_at' => (string)$this->created_at,
                'last_edited_at' => (string)$this->last_edited_at,
                'reply_to_post_id' => (int)$this->reply_to_post_id,
                'use_markdown' => (bool)$this->markdown,
                'use_indentation' => (bool)$this->use_indentation,
                'is_component' => (bool)$this->is_component,
                'is_post_comment' => (bool)$this->is_post_comment,
                'up_votes' => (int)$this->up_votes,
                'xianyus' => (int)$this->xianyus,
                'shengfans' => (int)$this->shengfans,
                'replies' => (int)$this->replies,
                'is_folded' => (bool)$this->is_folded,
                'is_bianyuan' => (bool)$this->is_bianyuan,
                'last_responded_at' => (string)$this->last_responded_at,
            ],
        ];
    }
}
