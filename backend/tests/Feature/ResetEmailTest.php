<?php

namespace Tests\Feature;

use Tests\TestCase;
use DB;
use App\Models\User;
use App\Models\PasswordReset;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Hash;
use Redis;
use Cache;

class ResetEmailTest extends TestCase
{
    /** @test 
     *
    */
    public function anyone_can_reset_password_by_email()
    {
        $user = factory('App\Models\User')->create();
        Cache::flush();
        $data=['email' => $user->email];
        $response = $this->post('api/password/email', $data)
        ->assertStatus(409)//当天注册用户
        ->assertJson([
            'code' => 409,
            'data' => [
              'email' => $user->email
            ],
        ]);

        $response = $this->post('api/password/email', $data)
        ->assertStatus(409);//当前ip已于10分钟内提交过重置密码请求。

        Cache::flush();
        $response = $this->post('api/password/email',['email' => '111'] )
        ->assertStatus(422)//邮箱格式错误
        ->assertJson([
            'code' => 422,
            'data' => [
              "message"=> "validation failed"
         ]
        ]);

        $response = $this->post('api/password/email',['email' => '111@163.com'] )
        ->assertStatus(404)//邮箱账户不存在
        ->assertJson([
            'code' => 404,
            'data' => [
              'email' => '111@163.com'
            ]
        ]);

        $user_update=User::where('email',$user->email)->update(['created_at' =>Carbon::now()->subDays(2)]);
        Cache::flush();
        $response = $this->post('api/password/email', $data)
        ->assertStatus(200);
        
        $token=$response->original['data']['token'];
        //$user_update=User::where('email',$user->email)->update(['email_verified_at' =>Carbon::now()->subDays(2)]);
        
        $request=[
          'token' => $token,
          'password' => '111'
        ];
        $response = $this->post('api/password/reset_via_email', $request)
        ->assertStatus(422);    //密码格式错误

        array_set($request, 'password', '1111111');
          $response = $this->post('api/password/reset_via_email', [
            'token' => 'token',
            'password' => '122111'
          ])
          ->assertJson([
              'code' => 404,
              'data' => [
                'token' => 'token'
              ]
          ]);    //cache中token不存在或过期  60min

          $user_update=User::where('email',$user->email)->update(['email_verified_at' =>Carbon::now()->subDays(2)]);
          $response = $this->post('api/password/reset_via_email', [
            'token' => $token,
            'password' => '111111'
          ])
          ->assertJson([
              'code' => 200,
              'data' => [
                'token' => $token
              ]
          ]); 
          $response = $this->post('api/password/reset_via_email', [
            'token' => $token,
            'password' => '111111'
          ])
          ->assertStatus(422); //token一次性有效
          
    $response =$this->post('api/login',['email'=>$user->email,'password'=>'111111'])
    ->assertStatus(200)
    ->assertJsonStructure([
        'code',
        'data' => [
            'token',
        ],
    ]);
  }
  }
