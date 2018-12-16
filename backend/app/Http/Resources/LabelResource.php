<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LabelResource extends JsonResource
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
            'type' => 'label',
            'id' => $this->id,
            'attributes' => [
                'label_name' => $this->label_name,
            ]
        ];
    }
}
