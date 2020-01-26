<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Storage;
use Illuminate\Console\Command;
use Carbon;
use DB;
use Log;
use StringProcess;


class TransferReviewToPostInfo extends Command
{

    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'data:transferReviewToPostInfo';

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
        // move reviews to post_info table
        DB::table('reviews')->where('post_id','>',1488113)->orderBy('post_id')->chunk(2, function ($reviews) {
            $review_ids = $reviews->pluck('post_id')->toArray();
            $existing_ids = DB::table('post_infos')->whereIn('post_id',$review_ids)->pluck('post_id')->toArray();
            if($existing_ids){
                dd($existing_ids);
            }
            $inserts = $reviews->map(function ($review) use($existing_ids){
                if(!$existing_ids||!in_array($review->post_id, $existing_ids)){
                    return [
                        'post_id' => $review->post_id,
                        'reviewee_id' => $review->thread_id,
                        'reviewee_component_id' => $review->thread_component_id,
                        'recommend' => $review->recommend,
                        'editor_recommend' => $review->editor_recommend,
                        'rating' => $review->rating,
                        'redirect_count' => $review->redirect_count,
                    ];
                }
            })->toArray();

            DB::table('post_infos')->insert($inserts);
            dd('break');
        });
    }

}
