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
        ->assertStatus(200);

        $response = $this->post('api/quote', ['body' => $body])
        ->assertStatus(422);

        //还应该继续验证：匿名是否可用，题头返回的数据格式是否对应。可以参考ThreadTest里面的对应代码
    }

    /** @test */
    public function a_guest_can_not_create_quote()
    {
        $body = $this->faker->sentence;
        $response = $this->post('api/quote', ['body' => $body])
        ->assertStatus(401);
    }
}
