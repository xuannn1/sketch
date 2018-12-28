<?php

namespace App\Http\Resources\ThreadResources;

use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
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
            ]
        ];
    }

}
