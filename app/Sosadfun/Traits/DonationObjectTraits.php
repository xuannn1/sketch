<?php
namespace App\Sosadfun\Traits;

use Cache;
use Carbon;

trait DonationObjectTraits{
    public function RecentDonations()
    {
        return Cache::remember('recent_donations', 20, function (){
            return \App\Models\HistoricalDonationRecord::with('author')
            ->where('donated_at','>',Carbon::now()->subMonth(1))
            ->orderBy('donation_amount','desc')
            ->orderBy('donated_at','asc')
            ->get();
        });
    }

}
