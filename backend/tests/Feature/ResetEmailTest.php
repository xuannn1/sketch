<?php

namespace Tests\Feature;

use Tests\TestCase;

class ResetEmailTest extends TestCase
{
    /** @test */
    public function anyone_can_reset_password_by_email()
    {
        $user = factory('App\Models\User')->create();
        // TODO 这里需要重新修补test
         // $data=['email' => $user->email];
        // $response = $this->post('api/password/email', $data)
        // ->assertStatus(403)
        // ->assertJson([
        //     'code' => 403,
        //     'data' => [
        //       'email' => $user->email
        //     ],
        // ]);
        // $response = $this->post('api/password/email', ['email' => '1@163.com'])
        // ->assertStatus(404)
        // ->assertJson([
        //     'code' => 404,
        //     'data' => [
        //       'email' => '1@163.com'
        //     ],
        // ]);
        // $response = $this->post('api/password/email', ['email' => '12345'])
        // ->assertStatus(422)
        // ->assertJson([
        //     'code' => 422
        // ]);

    }


}
