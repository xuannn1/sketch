<?php

namespace Tests\Feature;

use Tests\TestCase;

class QuoteTest extends TestCase
{
    /** @test */
    public function an_authorised_user_can_create_quote()
    {
        $user = factory('App\Models\User')->create();
        $this->actingAs($user, 'api');

        $body = $this->faker->sentence;
        $response = $this->post('api/quote', ['body' => $body])
        ->assertStatus(200)
        ->assertJsonStructure([
            'code',
            'data' => [
                'body',
                'user_id',
                'is_anonymous',
                'id',
            ],
        ])
        ->assertJson([
            'code' => 200,
            'data' => [
              'body' => $body,
              'user_id' => $user->id,
              'is_anonymous' => 0,
            ],
        ]);

        $response = $this->post('api/quote', ['body' => $body])
        ->assertStatus(422);
    }

    /** @test */
    public function an_authorised_user_can_create_quote_anonymously()//用户可匿名发表题头
    {
        $user = factory('App\Models\User')->create();
        $this->actingAs($user, 'api');

        $body = $this->faker->sentence;
        $majia = 'niming';
        $response = $this->post('api/quote', ['body' => $body, 'is_anonymous' => 1, 'majia' => $majia])
        ->assertStatus(200)
        ->assertJsonStructure([
            'code',
            'data' => [
                'body',
                'user_id',
                'is_anonymous',
                'majia',
                'id',
            ],
        ])
        ->assertJson([
            'code' => 200,
            'data' => [
              'body' => $body,
              'user_id' => $user->id,
              'is_anonymous' => 1,
              'majia' => $majia,
            ],
        ]);

        $response = $this->post('api/quote', ['body' => $body])
        ->assertStatus(422);
    }

    /** @test */
    public function a_guest_can_not_create_quote()
    {
        $body = $this->faker->sentence;
        $response = $this->post('api/quote', ['body' => $body])
        ->assertStatus(401);
    }
}
