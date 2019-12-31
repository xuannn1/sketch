<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuoteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if (!$this->is_anonymous){
            $author = new UserBriefResource($this->whenLoaded('author'));
        }else{
            $author = [];
        }
        return [
            'type' => 'quote',
            'id' => (int)$this->id,
            'attributes' => [
                'body' => (string)$this->body,
                'xianyu' => (int)$this->xianyu,
                'is_anonymous' => (bool)$this->is_anonymous,
                'majia' => (string)$this->majia,
            ],
            'author' => $author,
        ];
    }
}
