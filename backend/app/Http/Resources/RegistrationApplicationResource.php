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
        return [
            'email' => (string)$this->email,
            'has_quizzed' => (bool)$this->has_quizzed,
            'is_email_verified' => (bool)$this->email_verified_at,
            'is_essay_submitted' => (bool)$this->submitted_at,
            'is_in_cooldown' => (bool)($this->submitted_at > Carbon::now()->subDays(config('constants.application_cooldown_days'))),
            'is_passed' => (bool)$this->is_passed,
        ];
    }
}
