<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use App\Helpers\Helper;

class TrimSpaces extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clearsystem:trimspaces';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'remove extra spaces in each chapter';

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
        $posts = Post::where('maintext','=',1)->get();
        $count = 0;
        foreach($posts as $post){
            if($post->body !== Helper::trimSpaces($post->body)){
                $post->body = Helper::trimSpaces($post->body);
                $post->save();
                $count +=1;
            }
        }
        echo $count;
    }
}
