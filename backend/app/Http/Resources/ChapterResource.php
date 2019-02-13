<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChapterResource extends JsonResource
{
    /**
    * Transform the resource into an array.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return array
    */
    // 这是用于章节内容的呈现
    // thread.show, chapter.show
    public function toArray($request)
    {
            return [
                'type' => 'chapter',
                'id' => (int)$this->post_id,
                'attributes' => [
                    'volumn_id' => (int)$this->volumn_id,
                    'order_by' => (int)$this->order_by, 
                    'warning' => (string)$this->warning,
                    'annotation' => (string)$this->annotation,
                    'previous_id' => (int)$this->previous_chapter_id,
                    'next_id' => (int)$this->next_chapter_id,
                ],
            ];
    }
}
