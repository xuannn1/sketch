<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if((!$this->is_bianyuan)||(Auth::guard('api')->check())){
            $body = $this->body;
        }else{
            $body = '';
        }
        return [
            'type' => 'post',
            'id' => $this->id,
            'attributes' => [
                'body' => $body,
                'is_anonymous' => $this->is_anonymous,
                'majia' => $this->majia ?? '匿名咸鱼',
                'created_at' => $this->created_at ? $this->created_at->toDateTimeString():null,
                'last_edited_at' => $this->last_edited_at ? $this->last_edited_at->toDateTimeString():null,
                'reply_to_post_id' => $this->reply_to_post_id,
                'reply_to_post_preview' => $this->reply_to_post_preview,
                'is_maintext' => $this->is_maintext,
                'use_markdown' => $this->markdown,
                'use_indentation' => $this->use_indentation,
                'is_top' => $this->is_top,
                'is_highlighted' => $this->is_highlighted,
                'up_votes' => $this->up_votes,
                'down_votes' => $this->down_votes,
                'fold_votes' => $this->fold_votes,
                'funny_votes' => $this->funny_votes,
                'xianyus' => $this->xianyus,
                'shengfans' => $this->shengfans,
                'replies' => $this->replies,
                'is_folded' => $this->is_folded,
                'is_popular' => $this->is_popular,
                'is_longpost' => $this->is_longpost,
                'is_bianyuan' => $this->is_bianyuan,
                'last_responded_at' => $this->last_responded_at?  $this->last_responded_at->toDateTimeString():null,
            ],
            'relationships' => new PostRelationshipResource($this),
        ];
    }
}
