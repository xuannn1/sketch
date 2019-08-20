<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon;
use DB;

class ErrorCorrection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:errorCorrection';

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
        $records = \App\Models\HistoricalEmailModification::where('created_at','>','2019-08-20 02:52:01')->where('admin_revoked_at',null)->get();
        foreach($records as $record){
            $email_address = explode("@",$record->new_email);
            if($email_address[1]=='163.com'&&strlen($email_address[0])==6){
                $user = $record->user;
                DB::transaction(function()use($user, $record){
                    $user->forceFill([
                        'password' => str_random(60),
                        'remember_token' => str_random(60),
                        'activated' => 0,
                        'email' => $record->old_email,
                        'no_logging' => 1,
                    ])->save();
                    $record->admin_revoked_at = Carbon::now();
                    $record->save();
                }, 2);
                echo 'recovered user:'.$user->id.'|';
            }
        }
    }
}
