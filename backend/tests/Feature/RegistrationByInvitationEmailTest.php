<?php

namespace Tests\Feature;

use App\Http\Resources\QuizOptionResource;
use App\Models\QuizOption;
use App\Models\RegistrationApplication;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationByInvitationEmailTest extends TestCase
{
    /** @test */
    public function registration_by_invitation_email_submit_email()
    {
        $data = [
            'email' => 'hahahahaha'
        ];
        // 邮箱格式不符合的时候，不允许注册
        Artisan::call('cache:clear');
        $this->post('api/register/by_invitation_email/submit_email', $data)
            ->assertStatus(422);

        // qq邮箱不允许注册
        $data['email'] = 'tester@qq.com';
        Artisan::call('cache:clear');
        $this->post('api/register/by_invitation_email/submit_email', $data)
            ->assertStatus(422);

        // .con 报错
        $data['email'] = 'tester@tester.con';
        Artisan::call('cache:clear');
        $this->post('api/register/by_invitation_email/submit_email', $data)
            ->assertStatus(422);

        // 验证返回题目和格式
        $data['email'] = $this->faker->email;
        Artisan::call('cache:clear');
        $response = $this->post('api/register/by_invitation_email/submit_email', $data);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'code',
                'data' => [
                    'registration_application' => [
                        'id',
                        'type',
                        'attributes' => [
                            'email',
                            'has_quizzed',
                            'email_verified_at',
                            'submitted_at',
                            'is_passed',
                            'last_invited_at',
                            'is_in_cooldown'
                        ]
                    ],
                    'quizzes' => [
                        '*' => [
                            'id',
                            'type',
                            'attributes' => [
                                'body',
                                'hint',
                                'options' => [
                                    '*' => [
                                        'type',
                                        'id',
                                        'attributes'
                                    ]
                                ]
                            ]
                        ]
                    ],
                ],
            ]);

        // 验证是否取到了正确的题目
        $response=$response->decodeResponseJson();
        $id = $response["data"]["registration_application"]["id"];
        $quizzes_questions = RegistrationApplication::find($id)->quiz_questions;
        $returned_quizzes_questions = [];
        $this->assertCount(config('constants.registration_quiz_total'),$response["data"]["quizzes"]);
        foreach ($response["data"]["quizzes"] as $quiz) {
            $returned_quizzes_questions[] = $quiz["id"];
            $this->assertDatabaseHas('quizzes', ["id" => $quiz["id"]]);
            // 这里是验证是否所有选项都被选出来了
            $options_from_database = QuizOption::where('quiz_id',$quiz['id'])->orderBy('id')->pluck('id')->toArray();
            $options_from_returned = [];
            foreach ($quiz["attributes"]["options"] as $option) {
                $options_from_returned[] = $option['id'];
            }
            $this->assertEquals($options_from_database,$options_from_returned);
        }
        $this->assertEquals($quizzes_questions,implode(",",$returned_quizzes_questions));

        // 验证禁止频繁访问
        $response = $this->post('api/register/by_invitation_email/submit_email', $data)
            ->assertStatus(498);
    }

    /** @test */
    public function registration_by_invitation_email_resend_verification_email()
    {
        // 拒绝申请记录不存在的邮箱
        Artisan::call('cache:clear');
        $email_address = 'null@null.com';
        $this->get('api/register/by_invitation_email/resend_email_verification?email='.$email_address)
            ->assertStatus(404);

        // 拒绝未完成前序步骤的邮箱
        Artisan::call('cache:clear');
        $regapp = factory('App\Models\RegistrationApplication')->create();
        $email_address = $regapp->email;
        $this->get('api/register/by_invitation_email/resend_email_verification?email='.$email_address)
            ->assertStatus(411);

        // 拒绝短时间内重复要求重发验证码的
        Artisan::call('cache:clear');
        $regapp->update([
            'has_quizzed' => true,
            'send_verification_at' => Carbon::now()
        ]);
        $this->get('api/register/by_invitation_email/resend_email_verification?email='.$email_address)
            ->assertStatus(410);

        // 拒绝已经验证过邮箱了的
        Artisan::call('cache:clear');
        $regapp->update(['email_verified_at' => Carbon::now()]);
        $this->get('api/register/by_invitation_email/resend_email_verification?email='.$email_address)
            ->assertStatus(409);

        // 成功发送
        Artisan::call('cache:clear');
        $regapp->update([
            'email_verified_at' => null,
            'send_verification_at' => null
        ]);
        $this->get('api/register/by_invitation_email/resend_email_verification?email='.$email_address)
            ->assertStatus(200)->assertExactJson([
                "code" => 200,
                "data" => [
                    "email" => $email_address
                ]
            ]);

        // 验证禁止频繁访问
        $this->get('api/register/by_invitation_email/resend_email_verification?email='.$email_address)
            ->assertStatus(498);
    }

    /** @test */
    public function registration_by_invitation_email_resend_invitation_email()
    {
        // 拒绝申请记录不存在的邮箱
        Artisan::call('cache:clear');
        $email_address = 'null@null.com';
        $this->get('api/register/by_invitation_email/resend_invitation_email?email='.$email_address)
            ->assertStatus(404);

        // 拒绝未完成前序步骤的邮箱
        Artisan::call('cache:clear');
        $regapp = factory('App\Models\RegistrationApplication')->create();
        $email_address = $regapp->email;
        $this->get('api/register/by_invitation_email/resend_invitation_email?email='.$email_address)
            ->assertStatus(411);

        // 成功发送
        Artisan::call('cache:clear');
        $regapp->update([
            'has_quizzed' => true,
            'is_passed' => true
        ]);
        $this->get('api/register/by_invitation_email/resend_invitation_email?email='.$email_address)
            ->assertStatus(200)->assertExactJson([
                "code" => 200,
                "data" => [
                    "email" => $email_address
                ]
            ]);

        // 拒绝短时间内重复要求重发验证码的
        Artisan::call('cache:clear');
        $this->get('api/register/by_invitation_email/resend_invitation_email?email='.$email_address)
            ->assertStatus(409);

        // 拒绝已经成功通过点击邀请链接注册了的
        Artisan::call('cache:clear');
        $regapp->update(['user_id' => $this->faker->numberBetween($min = 10000, $max = 99999)]); // 产生一个随机用户id
        $this->get('api/register/by_invitation_email/resend_invitation_email?email='.$email_address)
            ->assertStatus(409);

        // 验证禁止频繁访问
        $this->get('api/register/by_invitation_email/resend_invitation_email?email='.$email_address)
            ->assertStatus(498);
    }

    /** @test */
    public function registration_by_invitation_submit_email_confirmation_token()
    {
        // 拒绝缺少token的请求
        Artisan::call('cache:clear');
        $data['email'] = 'null@null.com';
        $this->post('api/register/by_invitation_email/submit_email_confirmation_token', $data)
            ->assertStatus(422);

        // 拒绝申请记录不存在的邮箱
        Artisan::call('cache:clear');
        $data['email'] = 'null@null.com';
        $data['token'] = 'NotAValidToken';
        $this->post('api/register/by_invitation_email/submit_email_confirmation_token', $data)
            ->assertStatus(404);

        // 拒绝未完成前序步骤的邮箱
        Artisan::call('cache:clear');
        $regapp = factory('App\Models\RegistrationApplication')->create();
        $data['email'] = $regapp->email;
        $this->post('api/register/by_invitation_email/submit_email_confirmation_token', $data)
            ->assertStatus(411);

        // 拒绝被拉黑的邮箱/申请
        Artisan::call('cache:clear');
        $regapp->update([
            'is_forbidden' => true
        ]);
        $this->post('api/register/by_invitation_email/submit_email_confirmation_token', $data)
            ->assertStatus(499);

        // 拒绝错误的验证码
        Artisan::call('cache:clear');
        $regapp->update([
            'is_forbidden' => false,
            'has_quizzed' => true
        ]);
        $data['token'] = 'NotAValidToken';
        $this->post('api/register/by_invitation_email/submit_email_confirmation_token', $data)
            ->assertStatus(422);

        // 成功验证
        Artisan::call('cache:clear');
        $data['token'] = $regapp->email_token;
        $this->post('api/register/by_invitation_email/submit_email_confirmation_token', $data)
            ->assertStatus(200)->assertExactJson([
                "code" => 200,
                "data" => [
                    "email" => $data['email']
                ]
            ]);

        // 验证数据库是否已被更新
        $regapp = RegistrationApplication::where('email',$data['email'])->first();
        $this->assertNotNull($regapp->email_verified_at);
        $this->assertNotNull($regapp->ip_address_verify_email);

        // 拒绝已经验证过的
        Artisan::call('cache:clear');
        $this->post('api/register/by_invitation_email/submit_email_confirmation_token', $data)
            ->assertStatus(409);

        // 验证禁止频繁访问
        $this->post('api/register/by_invitation_email/submit_email_confirmation_token', $data)
            ->assertStatus(498);
    }
}
