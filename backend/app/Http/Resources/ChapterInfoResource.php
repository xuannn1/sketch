<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChapterInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
     //这是用于展示在书籍首页章节列表的chapter资源形式
     //api：book.show
    public function toArray($request)
    {
        return [
            'type' => 'chapter',
            'id' => (int)$this->id,
            'attributes' => [
                'volumn_id' => (int)$this->volumn_id,
                'order_by' => (int)$this->order_by,
            ],
        ];
    }
}
