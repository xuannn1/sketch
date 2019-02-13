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
        if($this->thread_id>0){
            $reviewee = new ThreadBriefResource($this->whenLoaded('reviewee'));
        }else{
            $reviewee = [];
        }
        return[
            'type' => 'review',
            'id' => (int) $this->post_id,
            'attributes' => [
                'thread_id' => (int) $this->thread_id,
                'recommend' => (bool) $this->recommend,
                'long' => (bool) $this->long,
                'author_disapprove' => (bool) $this->author_disapprove,
                'editor_recommend' => (bool) $this->editor_recommend,
                'rating' => (int) $this->rating,
                'redirect_count' => (int) $this->redirects,
            ],
            'reviewee' => $reviewee,
        ];
    }
}
