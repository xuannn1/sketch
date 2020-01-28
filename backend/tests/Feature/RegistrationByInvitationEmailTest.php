<?php

namespace Tests\Feature;

use App\Http\Resources\QuizOptionResource;
use App\Models\QuizOption;
use App\Models\RegistrationApplication;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationByInvitationEmailTest extends TestCase
{
    /** @test */
    public function registration_by_invitation_email()
    {
        $data = [
            'email' => 'hahahahaha'
        ];
        // 邮箱格式不符合的时候，不允许注册
        $this->post('api/register/by_invitation_email/submit_email', $data)
            ->assertStatus(422);

        // qq邮箱不允许注册
        $data['email'] = 'tester@qq.com';
        $this->post('api/register/by_invitation_email/submit_email', $data)
            ->assertStatus(422);

        // .con 报错
        $data['email'] = 'tester@tester.con';
        $this->post('api/register/by_invitation_email/submit_email', $data)
            ->assertStatus(422);

        // 验证返回题目和格式
        $data['email'] = 'tester7@tester.com';
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
    }
}
