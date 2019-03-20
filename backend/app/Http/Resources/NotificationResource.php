<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class NotificationResource extends JsonResource
{
    /**
    * Transform the resource into an array.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return array
    */
    public function toArray($request)
    {
        if ($this->whenLoaded('notifiable')){
            $notifiable = [
                'type' => (string) strtolower($this->votable_type),
                'id' => (int)$this->votable_id,
                'attributes' => [
                    'title' => (string)$this->notifiable->title,
                    'brief' => (string)$this->notifiable->id,
                    'body' => (string)$this->notifiable->body,
                ],
            ],
        }else{
            $notifiable = [];
        }
        return [
            'type' => 'notifications',
            'id' => (int)$this->id,
            'attributes' => [
                'operation' => (string) $this->operation,
                'seen' => (boolean) $this->seen,
                'created_at' => (string) $this->created_at,
            ],
            'notifiable' => $notifiable,
        ];
    }

}
