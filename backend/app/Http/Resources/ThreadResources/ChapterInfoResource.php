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
            'id' => $this->id,
            'attributes' => [
                'title' => $chapter->title,
                'brief' => $chapter->brief,
                'volumn_id' => $chapter->volumn_id,

                'is_anonymous' => $this->is_anonymous,
                'majia' => $this->majia ?? '匿名咸鱼',
                'created_at' => $this->created_at ? $this->created_at->toDateTimeString():null,
                'last_edited_at' => $this->last_edited_at ? $this->last_edited_at->toDateTimeString():null,
                'reply_to_post_id' => $this->reply_to_post_id,
                'is_maintext' => $this->is_maintext,
                'use_markdown' => $this->markdown,
                'use_indentation' => $this->use_indentation,
                'is_comment' => $this->is_comment,
                'up_votes' => $this->up_votes,
                'xianyus' => $this->xianyus,
                'shengfans' => $this->shengfans,
                'replies' => $this->replies,
                'is_folded' => $this->is_folded,
                'is_bianyuan' => $this->is_bianyuan,
                'last_responded_at' => $this->last_responded_at?  $this->last_responded_at->toDateTimeString():null,
            ],
        ];
    }
}
