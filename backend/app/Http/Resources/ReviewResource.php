<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if($this->reviewee){
            $reviewee = new ThreadBriefResource($this->reviewee);
        }else{
            $reviewee = [];
        }
        return[
            'type' => 'review',
            'id' => (int) $this->post_id,
            'attributes' => [
                'thread_id' => (int) $this->thread_id,
                'recommend' => (bool) $this->recommend,
                'rating' => (int) $this->rating,
            ],
            'reviewee' => $reviewee,
        ];
    }
}
