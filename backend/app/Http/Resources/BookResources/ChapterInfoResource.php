<?php

namespace App\Http\Resources\BookResources;

use Illuminate\Http\Resources\Json\JsonResource;

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
        return [
            'type' => 'chapter',
            'id' => $this->id,
            'attributes' => [
                'title' => $this->title,
                'brief' => $this->brief,
                'volumn_id' => $this->volumn_id,

                'created_at' => $this->mainpost->created_at ? $this->mainpost->created_at->toDateTimeString():null,
                'last_edited_at' => $this->mainpost->last_edited_at ? $this->mainpost->last_edited_at->toDateTimeString():null,
                'up_votes' => $this->mainpost->up_votes,
                'down_votes' => $this->mainpost->down_votes,
                'fold_votes' => $this->mainpost->fold_votes,
                'funny_votes' => $this->mainpost->funny_votes,
                'xianyus' => $this->mainpost->xianyus,
                'shengfans' => $this->mainpost->shengfans,
                'replies' => $this->mainpost->replies,
                'is_popular' => $this->mainpost->is_popular,
                'is_bianyuan' => $this->mainpost->is_bianyuan,
                'last_responded_at' => $this->mainpost->last_responded_at?  $this->mainpost->last_responded_at->toDateTimeString():null,
            ],
        ];
    }
}
