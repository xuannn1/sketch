<?php

namespace Tests\Feature;

//test
use Tests\TestCase;

class RegistrationTest extends TestCase
{

    /** @test */
    public function anyone_can_register_as_a_user()
    {
        $data = [
            'email' => $this->faker->email,
            'name' => $this->faker->name,
            'password' => 'password',
        ];
        $this->post('api/register', $data)
        ->assertStatus(200)
        ->assertJsonStructure([
            'code',
            'data' => [
                'token',
                'name',
            ],
        ])
        ->assertJson([
            'code' => 200,
            'data' => [
                'name' => $data['name'],
            ],
        ]);

        $this->post('api/login',$data)
        ->assertStatus(200)
        ->assertJsonStructure([
            'code',
            'data' => [
                'token',
            ],
        ]);
    }
}
