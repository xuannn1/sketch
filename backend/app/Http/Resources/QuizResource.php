<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\QuizOptionResource;

class QuizResource extends JsonResource
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
            'type' => 'quiz',
            'id' => (int)$this->id,
            'attributes' => [
                'body' => (string)$this->body,
                'hint' => (string)$this->hint,
                $this->mergeWhen(auth('api')->user() && auth('api')->user()->isAdmin(), [
                    'type' => $this->type,
                    'level' => $this->quiz_level,
                    'quiz_count' => $this->quiz_count,
                    'correct_count' => $this->correct_count,
                    'edited_at' => (string)$this->edited_at
                ]),
                'options' => QuizOptionResource::collection($this->random_options)
            ]
        ];
    }
}
