<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class RegistrationApplicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $cooldown = (bool)($this->submitted_at > Carbon::now()->subDays(config('constants.application_cooldown_days')));
        return [
            'id' => $this->id,
            'type' => 'registration_application',
            'attributes' => [
                'email' => (string)$this->email,
                'has_quizzed' => (bool)$this->has_quizzed,
                'email_verified_at' => (string)$this->email_verified_at,
                'submitted_at' => (string)$this->submitted_at,
                'is_passed' => (bool)$this->is_passed,
                'last_invited_at' => (string)$this->last_invited_at,
                'is_in_cooldown' => (bool)$cooldown
            ]
        ];
    }
}
