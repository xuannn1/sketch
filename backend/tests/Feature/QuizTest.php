<?php

namespace Tests\Feature;

use App\Models\Quiz;
use App\Models\QuizOption;
use App\Models\RegistrationApplication;
use App\Models\User;
use App\Models\UserInfo;
use App\Sosadfun\Traits\QuizObjectTraits;
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


    /** @test **/
    public function submit_quiz()
    {
        // 未登录时报错
        $this->post('api/quiz/submit_quiz')
            ->assertStatus(401);

        $user = factory('App\Models\User')->create();
        $this->actingAs($user, 'api');

        // 拒绝缺少quizzes的请求
        $this->post('api/quiz/submit_quiz')
            ->assertStatus(422);

        $this->get('api/quiz/get_quiz?level=1');
        $user_info = UserInfo::find($user->id);

        // 拒绝提交题目不匹配的请求
        $quiz_questions = array_map('intval',explode(',',$user_info->quiz_questions));
        $data['quizzes'] = [
            ['id' => 1, 'answer' => '1']
        ];
        $this->post('api/quiz/submit_quiz', $data)
            ->assertStatus(444);

        // 成功提交，答错数量太多
        unset($data['quizzes']);
        // 直接填充错误答案。填充方式为：如果正确选项数量大于1个，则只填充第一个选项；如果正确选项数量只有1个，则填充所有选项
        foreach ($quiz_questions as $quiz_question) {
            $possible_answers = QuizObjectTraits::find_quiz_set($quiz_question)->quiz_options;
            $correct_answer = $possible_answers->where('is_correct',true)->pluck('id')->toArray();
            if (count($correct_answer) > 1) {
                $data['quizzes'][] = ['id' => $quiz_question, 'answer' => $correct_answer[0]];
            } else {
                $data['quizzes'][] = ['id' => $quiz_question, 'answer' => implode(',',$possible_answers->pluck('id')->toArray())];
            }

        }

        $result = $this->post('api/quiz/submit_quiz', $data)
            ->assertStatus(200)->assertJsonStructure([
                'code',
                'data' => [
                    'id',
                    'type',
                    'attribute' => [
                        'is_passed',
                        'is_quiz_level_up',
                        'current_quiz_level'
                    ],
                    'quizzes' => [
                        '*' => [
                            'type',
                            'id',
                            'attributes' => [
                                'body',
                                'hint',
                                'correct_answer',
                                'options' => [
                                    '*' => [
                                        'type',
                                        'id',
                                        'attributes' => [
                                            'body',
                                            'explanation'
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]);

        // 验证返回的答题正误
        $result->assertJsonFragment([
            'is_passed' => false,
            'is_quiz_level_up' => false,
            'current_quiz_level' => 1
        ]);

        // 验证quiz_question有没有被清空
        $this->assertNull(UserInfo::find($user->id)->quiz_questions);

        // 成功提交，答题全对
        // 先重新刷新一次model
        UserInfo::find($user->id)->update(['quiz_questions' =>'']);

        $this->get('api/quiz/get_quiz?level=1');
        unset($data['quizzes']);
        // 直接填充正确答案
        foreach ($quiz_questions as $quiz_question) {
            $data['quizzes'][] = ['id' => $quiz_question, 'answer' => implode(',',QuizObjectTraits::find_quiz_set($quiz_question)->quiz_options->where('is_correct',true)->pluck('id')->toArray())];
        }
        $response = $this->post('api/quiz/submit_quiz', $data);
        $response->assertStatus(200)->assertExactJson([
            'code' => 200,
            'data' => [
                'id' => $user->id,
                'type' => 'quiz_result',
                'attribute' => [
                    'is_passed' => true,
                    'is_quiz_level_up' => true,
                    'current_quiz_level' => 1
                ]
            ]
        ])->assertDontSee('quizzes'); // 全对时不用出现'quizzes'字段

        // 验证quiz_question有没有被清空
        $this->assertNull(UserInfo::find($user->id)->quiz_questions);

        // 验证用户数据库是否已被更新
        $new_user = User::find($user->id);
        $this->assertGreaterThanOrEqual(1,$new_user->level);
        $this->assertEquals(2, $new_user->quiz_level);
    }

}
