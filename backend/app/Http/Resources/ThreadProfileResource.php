<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ThreadProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if((!$this->bianyuan)||(Auth::guard('api')->check())){
            $body = $this->body,
        }else{
            $body = '',
        }

        return [
            'type' => 'thread',
            'id' => $this->id,
            'attributes' => [
                'title' => $this->title,
                'brief' => $this->brief,
                'body' => $body,
                'is_anonymous' => $this->is_anonymous,
                'majia' => $this->majia ?? '匿名咸鱼',
                'created_at' => $this->created_at,
                'xianyus' => $this->xianyus,
                'shengfans' => $this->shengfans,
                'views' => $this->views,
                'replies' => $this->replies,
                'collections' => $this->collections,
                'downloads' => $this->downloads,
                'jifen' => $this->jifen,
                'weighted_jifen' => $this->weighted_jifen,
                'is_locked' => $this->is_locked,
                'is_public' => $this->is_public,
                'is_bianyuan' => $this->is_bianyuan,
                'no_reply' => $this->no_reply,
                'is_top' => $this->is_top,
                'is_popular' => $this->is_popular,
                'is_highlighted' => $this->is_highlighted,
                'last_responded_at' =>$this->last_responded_at,
                'book_status' => config('constants.book_info.book_status_info')[$this->book_status],
                'book_length' => config('constants.book_info.book_length_info')[$this->book_length],
                'sexual_orientation' => config('constants.book_info.sexual_orientation_info')[$this->sexual_orientation],
                'last_added_chapter_at' =>$this->last_added_chapter_at,
                'last_chapter_id' => $this->last_chapter_id,
            ],
            'relationships' => new ThreadRelationshipResource($this),
        ];
    }
}
