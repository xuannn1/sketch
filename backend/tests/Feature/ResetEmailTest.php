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

class ResetEmailTest extends TestCase
{
    /** @test 
     * 建议用实际存在的邮箱进行测试 200 的流程
    */
    public function anyone_can_reset_password_by_email()
    {
        $user = factory('App\Models\User')->create();
        $data=['email' => $user->email];
        $response = $this->post('api/password/email', $data)
        ->assertStatus(409)//当天注册用户
        ->assertJson([
            'code' => 409,
            'data' => [
              'email' => $user->email
            ],
        ]);
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
        $response = $this->post('api/password/email', $data)
        ->assertStatus(200)
        ->assertJson([
            'code' => 200,
            'data' => $data
        ]);

    }

    public function anyone_can_reset_password_by_token()
    {
        $user = factory('App\Models\User')->create();
        $user_update=User::where('email',$user->email)->update(['email_verified_at' =>Carbon::now()->subDays(2)]);
          $reset=PasswordReset::Create([
            'email' => $user->email,
            'token' => bcrypt('807a30c807ce5c9a1e9ae9a30d7e3cb82c87f2e8b30fa7bacabc80a5d651b201'),
            'created_at' => Carbon::now()->subMinutes(40)
            ]);
            $request=[
              'email' => $user->email,
              'token' => '807a30c807ce5c9a1e9ae9a30d7e3cb82c87f2e8b30fa7bacabc80a5d651b201',
              'password' => '111'
            ];
            $response = $this->post('api/password/reset', $request)
            ->assertStatus(422);    //密码格式错误
            array_set($request, 'password', '1111111');
              $response = $this->post('api/password/reset', $request)
              ->assertJson([
                  'code' => 422,
                  'data' => $request['token']//'807a30c807ce5c9a1e9ae9a30d7e3cb82c87f2e8b30fa7bacabc80a5d651b201'
              ]);    //token过期  
              $user_update=DB::table('password_resets')->where('email',$user->email)->update(
                [ 'created_at' => Carbon::now()->subMinutes(10)]);
              $response = $this->post('api/password/reset', $request)
              //->assertStatus(200)
              ->assertJson([
                  'code' => 200,
                  'data' => '200'
              ]); 
             
              }
}
