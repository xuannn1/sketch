<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewBriefResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return[
            'type' => 'review',
            'id' => (int) $this->post_id,
            'attributes' => [
                'thread_id' => (int) $this->thread_id,
                'recommend' => (bool) $this->recommend,
                'rating' => (int) $this->rating,
            ]
        ];
    }
}
