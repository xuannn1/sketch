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
    protected $description = 'to recalculate jifen for threads, weighted_jifen for books';

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
        $collections = DB::table('collections')
        ->where('user_id',297043)
        ->get();

        $new_collections = $collections->map(function ($collection) {
            $collection->{'user_id'} = 1;
            unset($collection->id);
            return (array)$collection;
        });
        DB::table('collections')->insert($new_collections->toArray());

    }
}
