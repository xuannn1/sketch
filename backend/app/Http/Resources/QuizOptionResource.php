<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuizOptionResource extends JsonResource
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
            'type' => 'quiz_option',
            'id' => (int)$this->id,
            'attributes' => [
                'body' => (string)$this->body,
                'explanation' => (string)$this->explanation,
                $this->mergeWhen(auth('api')->check() && auth('api')->user() && auth('api')->user()->isAdmin(), [
                    'quiz_id' => (int)$this->quiz_id,
                    'is_correct' => (bool)$this->is_correct,
                    'select_count' => (int)$this->select_count,
                    'edited_at' => (string)$this->edited_at
                ]),
            ]
        ];
    }
}
