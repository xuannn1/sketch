<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserReminderResource extends JsonResource
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
            'type' => 'user_info',
            'id' => (int)$this->user_id,
            'attributes' => [
                // TODO
                // all reminders in user_info return here
                // unread_reminders
                // upvote_reminders ...
                // public_notice_id
            ],
        ];
    }
}
