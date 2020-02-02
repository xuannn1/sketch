<?php

namespace Tests\Feature;

use App\Models\QuizOption;
use App\Models\RegistrationApplication;
use App\Models\UserInfo;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QuizTest extends TestCase
{
    /** @test */
    public function get_quiz()
    {

        // 未登录时报错
        $this->get('api/quiz/get_quiz')
            ->assertStatus(401);

        $user = factory('App\Models\User')->create();
        $this->actingAs($user, 'api');

        // 不存在对应level时报错
        $this->get('api/quiz/get_quiz?level=100')
            ->assertStatus(404);

        // 验证返回题目和格式
        $response = $this->get('api/quiz/get_quiz');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'code',
                'data' => [
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
        $id = $user->id;
        $quizzes_questions = UserInfo::find($id)->quiz_questions;
        $returned_quizzes_questions = [];
        $this->assertCount(config('constants.quiz_test_number'),$response["data"]["quizzes"]);
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
