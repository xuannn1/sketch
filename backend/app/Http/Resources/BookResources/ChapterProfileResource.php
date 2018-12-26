<?php

namespace App\Http\Resources\BookResources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChapterProfileResource extends JsonResource
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
                'annotation' => $this->annotation,
                'annotation_infront' => $this->annotation_infront,
                'previous_chapter_id' => $this->previous_chapter_id,
                'next_chapter_id' => $this->next_chapter_id,
            ],
        ];
    }
}
