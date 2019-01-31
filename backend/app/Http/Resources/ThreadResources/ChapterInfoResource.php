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
        if($request){
            $chapter = $this->chapter;
            return [
                'type' => 'chapter',
                'id' => (int)$this->id,
                'attributes' => [
                    'thread_id' => (int)$this->thread_id,
                    'title' => (string)$this->title,
                    'preview' => (string)$this->preview,
                    'volumn_id' => (int)$chapter->volumn_id,
                    'views' => (int)$chapter->views,
                    'characters' => (int)$chapter->characters,
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
        }else{
            return [];
        }
    }
}
