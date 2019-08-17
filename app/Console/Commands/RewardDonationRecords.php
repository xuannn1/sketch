<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;

class RewardDonationRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:rewardDonationRecords';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'reward donation records that has user and not claimed';

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
        $patreons = \App\Models\Patreon::on('mysql::write')->where('is_approved', 0)->get();
        foreach($patreons as $patreon){
            $patreon->sync_records();
        }
        $records = \App\Models\HistoricalDonationRecord::on('mysql::write')->where('user_id','>',0)->where('is_claimed',0)->get();
        foreach($records as $record){
            $record->reward_user();
        }
    }
}
