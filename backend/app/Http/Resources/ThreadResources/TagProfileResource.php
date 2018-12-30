<?php

namespace App\Http\Resources\ThreadResources;

use Illuminate\Http\Resources\Json\JsonResource;

class TagProfileResource extends JsonResource
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
            'type' => 'tag',
            'id' => $this->id,
            'attributes' => [
                'tag_name' => $this->tag_name,
                'tag_explanation' => $this->tag_explanation,
                'tag_type' => $this->tag_type,
                'is_bianyuan' => $this->is_bianyuan,
                'is_primary' => $this->is_primary,
                'channel_id' => $this->channel_id,
                'parent_id' => $this->parent_id,
                'tagged_books' => $this->tagged_books,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ]
        ];
    }
}
