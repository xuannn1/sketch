<?php
namespace App\Sosadfun\Traits;

use Cache;
use Carbon;

trait DonationObjectTraits{
    public function RecentDonations($page)
    {
        return Cache::remember('recent_donations-P'.$page, 20, function () use($page){
            return \App\Models\HistoricalDonationRecord::with('author')
            ->where('donated_at','>',Carbon::now()->subMonth(1))
            ->orderBy('donation_amount','desc')
            ->orderBy('donated_at','asc')
            ->paginate(config('preference.records_per_page'))
            ->appends(['page'=>$page]);
        });
    }

}
