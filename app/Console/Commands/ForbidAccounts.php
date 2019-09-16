<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon;
use DB;
use Log;

class ForbidAccounts extends Command
{
    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'data:forbidAccounts';

    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'a temporaral commander for things';

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
        // $records = DB::table('historical_email_modifications')->where('old_email', 'REGEXP', '^[a-zA-Z]{4}[0-9]{2}@163.com')
        // ->where('created_at','>','2019-08-24 00:00:00')->get();
        //
        // foreach($records as $record){
        //     $user = \App\Models\User::find($record->user_id);
        //     $info = $user->info;
        //     $user->forceFill([
        //            'remember_token' => str_random(60),
        //            'activated' => 0,
        //            'no_logging' => 1,
        //     ])->save();
        //     $info->forceFill([
        //            'no_logging_until' => '2050-08-24 00:00:00',
        //     ])->save();
        //     echo 'user_id: '.$user->id.'user_name: '.$user->name.'user_email'.$user->email."\n";
        // }

        //     $str1 = str_random(60);
        //     $str2 = str_random(60);
        //
        //     $users = DB::table('users')
        //     ->join('user_infos','user_infos.user_id','=','users.id')
        //     ->where('users.email', 'REGEXP', '^[a-zA-Z]{4}[0-9]{2}@163.com$')
        //     ->where('users.name', 'REGEXP', '^[a-zA-Z]{4}[0-9]{2}$')
        //     ->where('users.created_at','>','2019-08-24 00:00:00')
        //     ->where('users.created_at','<','2019-08-25 00:00:00')
        //     ->update([
        //         'users.password' => $str1,
        //         'users.remember_token' => $str2,
        //         'users.activated' => 0,
        //         'users.no_logging' => 1,
        //         'user_infos.no_logging_until' => '2050-08-24 00:00:00',
        //     ]);
        //
        //     $str1 = str_random(60);
        //     $str2 = str_random(60);
        //
        //     $users = DB::table('users')
        //     ->join('user_infos','user_infos.user_id','=','users.id')
        //     ->where('users.email', 'REGEXP', '^[a-zA-Z]{4}[0-9]{2}@163.com$')
        //     ->where('users.name', 'REGEXP', '^[a-zA-Z0-9]{12}$')
        //     ->where('users.created_at','>','2019-08-24 00:00:00')
        //     ->update([
        //         'users.password' => $str1,
        //         'users.remember_token' => $str2,
        //         'users.activated' => 0,
        //         'users.no_logging' => 1,
        //         'user_infos.no_logging_until' => '2050-08-24 00:00:00',
        //     ]);
        //
        //
        //     $str1 = str_random(60);
        //     $str2 = str_random(60);
        //
        //     $users = DB::table('users')
        //     ->join('user_infos','user_infos.user_id','=','users.id')
        //     ->where('users.email', 'REGEXP', '^([a-z]+)19[0-9]{6}@163.com$')
        //     ->where('users.name', 'REGEXP', '^([a-z0-9]+)$')
        //     ->where('users.activated',0)
        //     ->where('users.created_at','>','2019-08-20 00:00:00')
        //     // ->select('users.name','users.email','created_at')
        //     // ->get();
        //     ->update([
        //         'users.password' => $str1,
        //         'users.remember_token' => $str2,
        //         'users.activated' => 0,
        //         'users.no_logging' => 1,
        //         'user_infos.no_logging_until' => '2050-08-24 00:00:00',
        //     ]);
        //
        //     $str1 = str_random(60);
        //     $str2 = str_random(60);
        //
        //     $users = DB::table('users')
        //     ->join('historical_email_modifications','historical_email_modifications.user_id','=','users.id')
        //     ->join('user_infos','user_infos.user_id','=','users.id')
        //     ->where('historical_email_modifications.old_email', 'REGEXP', '^([a-z]+)19[0-9]{6}@163.com$')
        //     ->where('historical_email_modifications.old_email_verified_at', '=', null)
        //     ->where('users.name', 'REGEXP', '^([a-z0-9]+)$')
        //     ->where('users.created_at','>','2019-08-20 00:00:00')
        //     ->update([
        //         'users.password' => $str1,
        //         'users.remember_token' => $str2,
        //         'users.activated' => 0,
        //         'users.no_logging' => 1,
        //         'user_infos.no_logging_until' => '2050-08-24 00:00:00',
        //     ]);

        //删除封号
        // $records = DB::table('historical_email_modifications as h')
        // ->join('users as u','h.user_id','=','u.id')
        // ->where('h.old_email', 'REGEXP', '^[a-z]{4,5}[0-9]{5,6}@163.com$')
        // ->where('u.name', 'REGEXP', '^[a-zA-Z0-9]{6,11}$')
        // ->where('u.created_at','>','2019-08-16 00:00:00')
        // ->orderBy('u.id','desc')
        // ->get();
        //
        // foreach($records as $record){
        //     $user = \App\Models\User::find($record->user_id);
        //     $info = $user->info;
        //     $user->forceFill([
        //         'remember_token' => str_random(60),
        //         'activated' => 0,
        //         'no_logging' => 1,
        //         ])->save();
        //     $info->forceFill([
        //         'no_logging_until' => '2050-08-24 00:00:00',
        //         ])->save();
        //     echo 'user_id: '.$user->id.'|user_name: '.$user->name.'|old_email: '.$record->old_email.'|created_at: '.$user->created_at.'|user_email: '.$user->email."\n";
        // }

        $str1 = str_random(60);
        $str2 = str_random(60);

        $users = DB::table('users')
        ->join('user_infos','user_infos.user_id','=','users.id')
        ->where('users.email', 'REGEXP', '^[a-z]{4,5}[0-9]{5,6}@163.com$')
        ->where('users.name', 'REGEXP', '^[a-zA-Z0-9]{6,11}$')
        ->where('users.created_at','>','2019-09-10 00:00:00')
        ->where('users.created_at','<','2019-09-13 00:00:00')
        ->where('users.activated',0)
        // ->select('users.id','users.name')
        // ->get();
        ->update([
            'users.password' => $str1,
            'users.remember_token' => $str2,
            'users.activated' => 0,
            'users.no_logging' => 1,
            'user_infos.no_logging_until' => '2050-08-24 00:00:00',
        ]);
        // dd($users);
    }
}
