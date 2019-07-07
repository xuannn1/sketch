<?php
namespace App\Console\Commands\TemporaryTraits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait ModifyRewardNVoteTableTraits{

    public function modifyRewardNVoteTable()
    {
        $this->modifyRewardsTable();
        $this->modifyVotesTable();
    }

    public function modifyRewardsTable() //task 3
    {
        echo "started task 3 modify rewards table\n";
        if (!Schema::hasTable('rewards')) {
            Schema::create('rewards', function ($table) {
                $table->increments('id');
                $table->unsignedInteger('user_id')->index();
                $table->string('rewardable_type',10)->nullable()->index();
                $table->unsignedInteger('rewardable_id')->default(0)->index();
                $table->string('reward_type',10)->nullable()->index();
                $table->integer('reward_value')->default(0);//也有可能是负数
                $table->dateTime('created_at')->nullable();
            });
            echo "created rewards table\n";
        }
        echo "started storing shengfans to rewards\n";
        \App\Models\Shengfan::with('post')->chunk(1000, function ($shengfans) {
            $insert_shengfan = [];
            foreach($shengfans as $shengfan){
                $post = $shengfan->post;
                if($post->id>0&&$post->thread_id>0&&$post->user_id>0){
                    $shengfan_data = [
                        'user_id' => $shengfan->user_id,
                        'rewardable_type' => 'thread',
                        'rewardable_id' => $post->thread_id,
                        'reward_type' => 'shengfan',
                        'reward_value' => $shengfan->shengfan_num,
                        'created_at' =>$shengfan->created_at,
                    ];
                    array_push($insert_shengfan, $shengfan_data);
                }
            }
            DB::table('rewards')->insert($insert_shengfan);
            echo $shengfan->id."|";
        });
        echo "finished storing shengfans\n";
        echo "start storing xianyus\n";
        \App\Models\Xianyu::with('thread')->chunk(1000, function ($xianyus) {
            $insert_xianyu = [];
            foreach($xianyus as $xianyu){
                if($xianyu->thread_id>0){
                    $xianyu_data = [
                        'user_id' => $xianyu->user_id,
                        'rewardable_type' => 'thread',
                        'rewardable_id' => $xianyu->thread_id,
                        'reward_type' => 'xianyu',
                        'reward_value' => 1,
                        'created_at' =>$xianyu->created_at,
                    ];
                    array_push($insert_xianyu, $xianyu_data);
                }
            }
            DB::table('rewards')->insert($insert_xianyu);
            echo $xianyu->id."|";
        });
        echo "finished storing shengfans\n";
    }

    public function modifyVotesTable() //task 3
    {
        echo "start task updateVotesTable\n";
        if (!Schema::hasTable('votes')) {
            Schema::create('votes', function ($table) {
                $table->increments('id');
                $table->unsignedInteger('user_id')->index();
                $table->string('votable_type',10)->nullable()->index();
                $table->unsignedInteger('votable_id')->default(0)->index();
                $table->unsignedInteger('record_id')->default(0)->index();// 原来的记录地址
                $table->string('attitude_type',10)->nullable()->index();
                $table->dateTime('created_at')->nullable();
                // $table->unique(['user_id','votable_type','votable_id','attitude_type']);
            });
            echo "created votes table\n";
        }
        echo "started inserting votes to table\n";
        \App\Models\VotePosts::with('user','post')->chunk(1000, function ($votes) {
            $insert_votes = [];
            foreach($votes as $vote){
                $post = $vote->post;
                $user = $vote->user;
                if($post&&$user){
                    if($vote->upvoted){
                        $vote_data=[
                            'user_id' => $vote->user_id,
                            'votable_type' => 'post',
                            'attitude_type' => 'upvote',
                            'created_at' => $vote->upvoted_at,
                            'record_id' => $vote->id,
                            'votable_id' => $vote->post_id,
                        ];
                        array_push($insert_votes,$vote_data);
                    }
                }
            }
            DB::table('votes')->insert($insert_votes);
            echo $vote->id."|";
        });
        echo "started removing duplicate votes from table\n";
        DB::statement('
            DELETE v1 FROM votes v1
            INNER JOIN
            votes v2
            WHERE
            v1.id < v2.id AND v1.user_id = v2.user_id and v1.votable_id = v2.votable_id;
        ');
        echo "removed duplicate votes\n";
    }

}
