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
            'id' => (int)$this->id,
            'attributes' => [
                'tag_name' => (string)$this->tag_name,
                'tag_explanation' => (string)$this->tag_explanation,
                'tag_type' => (string)$this->tag_type,
                'is_bianyuan' => (bool)$this->is_bianyuan,
                'is_primary' => (bool)$this->is_primary,
                'channel_id' => (int)$this->channel_id,
                'parent_id' => (int)$this->parent_id,
                'tagged_books' => (int)$this->tagged_books,
                'created_at' => (string)$this->created_at,
                'updated_at' => (string)$this->updated_at,
            ]
        ];
    }
}
