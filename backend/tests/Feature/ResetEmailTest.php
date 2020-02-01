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
        ->assertStatus(412);//当天注册用户

        $response = $this->post('api/password/email', $data)
        ->assertStatus(498);//当前ip已于10分钟内提交过重置密码请求。

        Cache::flush();
        $response = $this->post('api/password/email',['email' => '111'] )
        ->assertStatus(422)//邮箱格式错误
        ->assertJson([
            'code' => 422,
            'data' => "邮箱格式不正确"
        ]);

        $response = $this->post('api/password/email',['email' => '111@163.com'] )
        ->assertStatus(404)//邮箱账户不存在
        ->assertJson([
            'code' => 404,
            'data' => '该邮箱账户不存在'
        ]);

        $user_update=User::where('email',$user->email)->update(['created_at' =>Carbon::now()->subDays(2)]);
        Cache::flush();
        $response = $this->post('api/password/email', $data)
        ->assertStatus(200);
        
        $token = str_random(40);
        Cache::put($token, $user->email, 60);
        $user_update=PasswordReset::where('email',$user->email)->update(['token' =>bcrypt($token)]);
        
        $request=[
          'token' => $token,
          'password' => '111'
        ];
        $response = $this->post('api/password/reset_via_email', $request)
        ->assertStatus(422);    //密码格式错误

        array_set($request, 'password', 'Aa1aa%a01A11saAD');
          $response = $this->post('api/password/reset_via_email', [
            'token' => 'token',
            'password' => 'Aa1aa%a01A11saAD'
          ])
          ->assertJson([
              'code' => 404,
              'data' => "token过期或不存在"
          ]);    //cache中token不存在或过期  60min

          Cache::put('token_test','111@163.com' ,60);
          $response = $this->post('api/password/reset_via_email', [
            'token' => 'token_test',
            'password' => 'Aa1aa%a01A11saAD'
          ])
          ->assertJson([
              'code' => 404,
              'data' => "找不到重置请求"
          ]); //email及token的配对不存在重置表

           $response = $this->post('api/password/reset_via_email', $request)
          ->assertJson([
              'code' => 200,
              'data' => 200
          ]); 
          $response = $this->post('api/password/reset_via_email', $request)
          ->assertStatus(404); //token一次性有效 token过期

          $response =$this->post('api/login',['email'=>$user->email,'password'=>"Aa1aa%a01A11saAD"])
          ->assertStatus(200)
          ->assertJsonStructure([
            'code',
            'data' => [
                'token',
            ],
      ]);
    }
  }
