<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            'type' => 'message',
            'id' => (int)$this->id,
            'attributes' => [
                'poster_id' => (int)$this->poster_id,
                'receiver_id' => (int)$this->receiver_id,
                'message_body' => $this->body,
                'created_at' => (string)$this->created_at,
                'seen' => (bool)$this->seen,
            ],
            'poster' => new UserBriefResource($this->poster),
            'receiver' => new UserBriefResource($this->receiver),
        ];
    }
}
