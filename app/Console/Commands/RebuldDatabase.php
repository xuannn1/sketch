<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
class RebuildDatabase extends Command
{
    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'rebuild:database';
    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'update database tables so it fits with new ER-sosad form';
    /**
    * Create a new command instance.
    *
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
    }
    /**
    * Execute the console command.
    *
    * @return mixed
    */
    public function handle()
    {
        $this->modifyUserTable();//task 01
    }

    public function modifyUserTable()//task 01
    {
        $this->updateUserInfoNIntro();// task 01.1
        // $this->deleteExtraUserColumns();// task 01.2
    }

    public function updateUserInfoNIntro()//task 01.1
    {
        // echo "task 01.1 start modifying users one by one\n";
        // \App\Models\User::chunk(1000, function ($users) {
        //     foreach($users as $user){
        //         $user_data['role']='';
        //         if($user->admin){
        //             $user_data['role']='admin';
        //         }elseif($user->group>10){
        //             $user_data['role']='editor';
        //         }
        //         $user_intro = [
        //             'user_id' => $user->id,
        //             'introduction' => $user->introduction,
        //             'updated_at' => Carbon::now(),
        //         ];
        //         $user_info = [
        //             'user_id' => $user->id,
        //             'shengfan' => $user->shengfan,
        //             'xianyu' => $user->xianyu,
        //             'jifen' => $user->jifen,
        //             'sangdian' => $user->sangdian,
        //             'exp' => $user->experience_points,
        //             'upvotes' => $user->upvoted,
        //             'brief_intro' => \App\Helpers\Helper::trimtext($user->introduction, 20),
        //             'majia' => $user->majia,
        //             'indentation' =>$user->indentation,
        //             'activation_token' => $user->activation_token,
        //             'invitation_token' => $user->invitation_token,
        //             'no_posting_until' => $user->no_posting,
        //             'no_logging_until' => $user->no_logging,
        //             'qiandao_at' => $user->lastrewarded_at,
        //             'continued_qiandao' => $user->continued_qiandao,
        //             'max_qiandao' =>$user->maximum_qiandao,
        //             'quiz_level' => $user->last_quizzed_at>0 ? 1:0,
        //             'no_stranger_msg' => !$user->receive_messages_from_strangers,
        //             'no_upvote_reminders' => $user->no_upvote_reminders,
        //             'clicks' =>  $user->clicks,
        //             'daily_clicks' =>  $user->daily_clicks,
        //             'reply_reminders' =>  $user->reply_reminders+$user->post_reminders+$postcomment_reminders,
        //             'upvote_reminders' => $user->upvote_reminders,
        //             'message_reminders' => $user->message_reminders,
        //             'public_notices' => $user->public_notices,
        //             'collection_threads_updates' => $user->collection_threads_updates,
        //             'collection_books_updates' => $user->collection_books_updates,
        //             'collection_statuses_updates' => $user->collection_statuses_updates,
        //             'login_ip' => $user->last_login_ip,
        //             'login_at' => $user->last_login,
        //             'created_at' => $user->created_at,
        //         ];
        //         if($user_data['role']){
        //             $user->update($user_data);
        //         }
        //         if($user_intro['introduction']){
        //             DB::table('user_intros')->insert($user_intro);
        //         }
        //         DB::table('user_infos')->insert($user_info);
        //     }
        //     echo $user->id."|";
        // });
    }

}
