<?php

namespace App\Http\Resources\ThreadResources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\AuthorIdentifierResource;

class ChapterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if($request){
            if((!$this->is_bianyuan)||(auth('api')->check())){
                $body = $this->body;
            }else{
                $body = '';
            }
            $chapter = $this->chapter;
            if($chapter){
                return [
                    'type' => 'chapter',
                    'id' => (int)$this->id,
                    'attributes' => [
                        'title' => (string)$chapter->title,
                        'brief' => (string)$chapter->brief,
                        'body' => (string)$body,
                        'volumn_id' => (int)$chapter->volumn_id,
                        'views' => (int)$chapter->views,
                        'characters' => (int)$chapter->characters,
                        'annotation' => (string)$chapter->annotation,
                        'annotation_infront' => (bool)$chapter->annotation_infront,
                        'previous_chapter_id' => (int)$chapter->previous_chapter_id,
                        'next_chapter_id' => (int)$chapter->next_chapter_id,
                        'created_at' => (string)$this->created_at,
                        'last_edited_at' => (string)$this->last_edited_at,
                        'use_markdown' => (bool)$this->markdown,
                        'use_indentation' => (bool)$this->use_indentation,
                        'up_votes' => (int)$this->up_votes,
                        'xianyus' => (int)$this->xianyus,
                        'shengfans' => (int)$this->shengfans,
                        'replies' => (int)$this->replies,
                        'is_bianyuan' => (bool)$this->is_bianyuan,
                        'last_responded_at' => (string)$this->last_responded_at,
                    ],
                ];
            }
        }
        return [];
    }
}
