<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;
use Carbon;
use DB;

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

        DB::table('patreons')
        ->join('historical_donation_records','historical_donation_records.donation_email','=','patreons.patreon_email')
        ->where('patreons.is_approved',0)
        ->update([
            'patreons.is_approved' => 1,
            'historical_donation_records.user_id' => DB::raw('patreons.user_id'),
        ]);

        $records = \App\Models\HistoricalDonationRecord::on('mysql::write')
        ->where('user_id','>',0)
        ->where('is_claimed', 0)
        ->where('donated_at','>',Carbon::now()->subMonth())
        ->get();

        foreach($records as $record){
            $record->reward_user();
        }

    }
}
