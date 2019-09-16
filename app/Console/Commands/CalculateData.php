<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon;
use DB;
use Log;

class CalculateData extends Command
{
    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'data:caculation';

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
        for($i=9;$i>=0;$i--){
            $users = DB::table('users')
            ->join('user_infos','user_infos.user_id','=','users.id')
            ->where('users.level',$i)
            ->where('users.role', null)
            ->select('users.id','user_infos.ham')
            ->get();

            $user_with_book_ids = DB::table('users')
            ->join('threads','threads.user_id','=','users.id')
            ->where('users.level',$i)
            ->where('users.role', null)
            ->where('threads.deleted_at','=',null)
            ->where('threads.is_public','=',1)
            ->where('threads.channel_id','<=',2)
            ->where('threads.total_char','>',500)
            ->distinct('users.id')
            ->select('users.id')
            ->get();

            $user_with_books = $users->filter(function ($value, $key) use($user_with_book_ids){
                if($user_with_book_ids->keyBy('id')->get($value->id)){
                    return $value;
                }
            });

            $user_without_books = $users->filter(function ($value, $key) use($user_with_book_ids){
                if($user_with_book_ids->keyBy('id')->get($value->id)){
                    return;
                }
                return $value;
            });

            $user_with_books_hams = $user_with_books->pluck('ham')->toArray();
            $user_without_books_hams = $user_without_books->pluck('ham')->toArray();

            echo 'user_level='.$i."\n";
            echo 'user_with_books_hams at level'.$i."\n";
            echo 'quartile 0.25='.$this->Quartile($user_with_books_hams, 0.25)."\n";
            echo 'quartile 0.5='.$this->Quartile($user_with_books_hams, 0.5)."\n";
            echo 'quartile 0.75='.$this->Quartile($user_with_books_hams, 0.75)."\n";
            echo 'count='.$user_with_books->count()."\n";

            echo 'user_without_books_hams at level'.$i."\n";
            echo 'quartile 0.25='.$this->Quartile($user_without_books_hams, 0.25)."\n";
            echo 'quartile 0.5='.$this->Quartile($user_without_books_hams, 0.5)."\n";
            echo 'quartile 0.75='.$this->Quartile($user_without_books_hams, 0.75)."\n";
            echo 'count='.$user_without_books->count()."\n";
        }
    }

    public function Quartile($Array, $Quartile) {
        sort($Array);
        $pos = (count($Array) - 1) * $Quartile;

        $base = floor($pos);
        $rest = $pos - $base;

        if( isset($Array[$base+1]) ) {
            return $Array[$base] + $rest * ($Array[$base+1] - $Array[$base]);
        } else {
            return $Array[$base];
        }
    }
}
