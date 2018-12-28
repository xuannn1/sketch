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
            $author = new AuthorIdentifierResource($this->author);
        }else{
            $author = [];
        }
        return [
            'type' => 'quote',
            'id' => $this->id,
            'attributes' => [
                'body' => $this->body,
                'xianyus' => $this->xianyus,
                'is_anonymous' => $this->is_anonymous,
                'majia' => $this->majia,
            ],
            'author' => $author,
        ];
    }
}
