<?php
namespace App\Console\Commands\TemporaryTraits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait modifyQuestionBoxTraits{

    public function modifyQuestionBox()
    {
        $this->createBoxes();
        $this->insertQuestionAnswerToBox();
    }

    public function createBoxes()
    {
        if(!Schema::hasColumn('questions', 'post_id')){
            Schema::table('questions', function($table){
                $table->unsignedInteger('post_id')->default(0)->index();
                echo "added new post id to questions table.\n";
            });
            echo "task added new post columns\n";
        }
        echo "task createBoxes\n";
        $questions = \App\Models\Question::all();
        foreach($questions as $question){
            if($question->answer_id>0){
                $user = \App\Models\User::find($question->user_id);
                $thread = \App\Models\Thread::where('user_id',$question->user_id)
                ->where('channel_id', 14)
                ->first();
                if(!$thread){
                    $thread_id = DB::table('threads')
                    ->insertGetId([
                        'user_id' => $question->user_id,
                        'title' => $user->name."的问题箱",
                        'brief' => "欢迎向我提问哦！",
                        'created_at' => $question->created_at,
                        'channel_id' => 14,
                    ]);
                    $user->info->default_box_id = $thread_id;
                    $user->info->save();
                }
            }
        }
        echo "created boxes.\n";
    }
    public function insertQuestionAnswerToBox()//15.2
    {
        echo "task insertQuestionAnswerToBox\n";
        $questions = \App\Models\Question::all();
        foreach($questions as $question){
            $thread = \App\Models\Thread::where('user_id',$question->user_id)
            ->where('channel_id', 14)
            ->first();
            if($thread&&$thread->id>0){
                $question_brief = \App\Helpers\Helper::trimtext($question->question_body, 50);
                $question_id = DB::table('posts')
                ->insertGetId([
                    'user_id' => $question->questioner_id,
                    'creation_ip' => $question->questioner_ip,
                    'brief' => $question_brief,
                    'body' => $question->question_body,
                    'anonymous' => true,
                    'created_at' => $question->created_at,
                    'type' => 'question',
                    'thread_id' => $thread->id,
                ]);
                $question->post_id=$question_id;
                $question->save();
                $answer = $question->answer;
                if($answer->id>0){
                    $answer_id = DB::table('posts')
                    ->insertGetId([
                        'user_id' => $question->user_id,
                        'brief' => \App\Helpers\Helper::trimtext($answer->answer_body, 50),
                        'body' => $answer->answer_body,
                        'created_at' => $answer->created_at,
                        'type' => 'answer',
                        'reply_to_id' => $question_id,
                        'reply_to_brief' => $question_brief,
                        'thread_id' => $thread->id,
                        'edited_at' =>$answer->updated_at,
                    ]);
                }
                echo $question->id.'|';
            }
        }
        echo "inserted questions and answers.\n";
    }
}
