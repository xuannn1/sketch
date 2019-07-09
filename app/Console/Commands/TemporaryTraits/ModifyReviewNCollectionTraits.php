<?php
namespace App\Console\Commands\TemporaryTraits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait ModifyReviewNCollectionTraits{

    public function modifyReviewNCollection()
    {
        $this->createReviewTable();
        $this->createReviewList();
        $this->createNewCollectionList();
        $this->insertReviewToCollectionList();
        $this->updateCollectionsTable();
    }

    public function createReviewTable()
    {
        echo "start createReviewTable\n";
        Schema::create('reviews', function ($table) {
            $table->unsignedInteger('post_id')->primary();
            $table->unsignedInteger('thread_id')->default(0)->index();//以后也允许登记外站书籍
            $table->unsignedInteger('thread_component_id')->default(0)->index();//对一些书籍，直接跳转到对应章节
            $table->boolean('recommend')->default(true);//是否对外推荐
            $table->boolean('long')->default(false);//是否属于长评，字数超过几百字xx算长评
            $table->boolean('editor_recommend')->default(false);//编辑推荐
            $table->tinyInteger('rating')->default(0);//评分，可以为零（不打分）
            $table->unsignedInteger('redirects')->default(0);//看完文评之后前进看书的比例
        });
        echo "finished createReviewTable\n";
    }
    public function createReviewList()
    {
        echo "start task12.2 createReviewList\n";
        $editor = \App\Models\User::where('name','废文网编辑组')->first();
        $list_id = DB::table('threads')->insertGetId([
            'channel_id' => 13,
            'user_id' => $editor->id,
            'title' => '往期编推总楼',
            'brief' => '好文共赏',
            'body' => '存放往期推文，如果能找到对应的编辑，那么文章会存放在编辑账户下，否则归在本楼。',
        ]);
        echo "task 12.2 created main list \n";
        echo "task 12.2 start move review \n";
        $recommends = \App\Models\RecommendBook::all();
        foreach($recommends as $recommend){
            if($recommend->valid){
                if($recommend->long){
                    //长推
                    $recommendation_post = \App\Models\Post::find($recommend->thread_id);
                    if($recommendation_post){
                        $post_id = DB::table('posts')->insertGetId([
                            'thread_id'=> $list_id,
                            'brief' => $recommend->recommendation,
                            'body' => $recommendation_post->body,
                            'created_at' => $recommend->created_at,
                            'type' => 'review',
                        ]);
                        DB::table('reviews')->insert([
                            'post_id' => $post_id,
                            'thread_id'=> 0,
                            'recommend' => true,
                            'long' => true,
                            'editor_recommend' =>true,
                            'redirects' => $recommend->clicks,
                        ]);
                    }
                }else{
                    //短推
                    $post_id = DB::table('posts')->insertGetId([
                        'thread_id'=> $list_id,
                        'brief' => $recommend->recommendation,
                        'body' => $recommend->recommendation,
                        'created_at' => $recommend->created_at,
                        'type' => 'review',
                    ]);
                    DB::table('reviews')->insert([
                        'post_id' => $post_id,
                        'thread_id'=> $recommend->thread_id,
                        'recommend' => true,
                        'editor_recommend' =>true,
                        'redirects' => $recommend->clicks,
                    ]);
                }
                echo $post_id,"|";
            }
        }

    }

    public function createNewCollectionList()//13.1
    {
        echo "start createNewCollectionList\n";

        if(!Schema::hasColumn('threads', 'old_list_id')){
            Schema::table('threads', function($table){
                $table->unsignedInteger('old_list_id')->default(0)->index();
            });
            echo "echo added old_list_id column to threads table.\n";
        }
        echo "task createList:\n";
        $collectionlists = \App\Models\CollectionList::where('type','<',4)->get();
        $insert_lists = [];
        foreach($collectionlists as $collectionlist){
            $list_data = [
                'user_id' => $collectionlist->user_id,
                'channel_id' => 13,
                'title' => $collectionlist->title,
                'brief' => $collectionlist->brief,
                'body' => $collectionlist->body,
                'view_count' => $collectionlist->viewed,
                'edited_at' => $collectionlist->lastupdated_at,
                'created_at' => $collectionlist->created_at,
                'anonymous' => $collectionlist->anonymous,
                'majia' => $collectionlist->majia,
                'old_list_id' => $collectionlist->id,
            ];
            array_push($insert_lists, $list_data);
        }
        DB::table('threads')->insert($insert_lists);
        echo "created new lists\n";

        DB::table('threads')
        ->join('user_infos','user_infos.user_id','=','threads.user_id')
        ->where('threads.channel_id','=',13)
        ->update([
            'user_infos.default_list_id' => DB::raw('threads.id')
        ]);
        echo "finished createNewCollectionList\n";
    }
    public function insertReviewToCollectionList()
    {
        if(!Schema::hasColumn('posts', 'collection_thread_id')){
            Schema::table('posts', function($table){
                $table->unsignedInteger('collection_thread_id')->default(0)->index();
            });
            echo "echo added collection_thread_id column to posts table.\n";
        }

        $collection_reviews = DB::table('collections')
        ->join('collection_lists','collections.collection_list_id','=','collection_lists.id')
        ->join('threads','threads.old_list_id','=','collection_lists.id')
        ->select('threads.id as list_id','collections.brief as brief','collections.body as body','collections.lastupdated_at as created_at','collection_lists.anonymous as anonymous','collection_lists.majia as majia','collections.item_id as collection_thread_id')
        ->get();

        $insert_posts = [];
        foreach ( $collection_reviews as $collection_review ){
            $post_data = [
                'thread_id'=> $collection_review->list_id,
                'brief' => \App\Helpers\Helper::trimtext($collection_review->body, 40),
                'body' => $collection_review->body,
                'created_at' => $collection_review->created_at,
                'type' => 'review',
                'anonymous' => $collection_review->anonymous,
                'majia' =>$collection_review->majia,
                'collection_thread_id' => $collection_review->collection_thread_id,
            ];
            array_push($insert_posts, $post_data);
        }
        DB::table('posts')->insert($insert_posts);
        echo "added collections into posts as reviews.\n";

        $prepare_review_data = DB::table('posts')
        ->where('type','=','review')
        ->where('collection_thread_id','>',0)
        ->get();

        foreach($prepare_review_data as $prepare_data){
            $insert_reviews = [];
            $review_data = [
                'post_id' => $prepare_data->id,
                'thread_id'=> $prepare_data->collection_thread_id,
                'recommend' => $prepare_data->body>0 ? true:false,
            ];
            array_push($insert_reviews, $review_data);
        }
        DB::table('reviews')->insert($insert_reviews);
        echo "added reviews for previous public collections.\n";
    }

    public function updateCollectionsTable()
    {
        DB::table('collection_lists')
        ->join('collections','collections.collection_list_id','=','collection_lists.id')
        ->join('threads','threads.old_list_id','=','collections.item_id')
        ->where('collection_lists.type','=',4)
        ->update([
            'collections.item_id' => DB::raw('threads.id'),
            'collections.collection_list_id' => 0,
        ]);
        echo "task transferred collection of list into regular collections\n";

        DB::table('collections')
        ->where('collections.collection_list_id','>',0)
        ->delete();
        echo "removed public collections from private collections\n";

        if(Schema::hasColumn('collections', 'item_id')){
            Schema::table('collections', function($table){
                $table->renameColumn('item_id', 'thread_id');
                echo "echo renamed collections column.\n";
            });
        }
        if(Schema::hasColumn('collections', 'collection_list_id')){
            Schema::table('collections', function($table){
                $table->dropColumn(['collection_list_id','brief','body','lastupdated_at','delete_thread_id']);
                echo "echo dropped collections columns.\n";
            });
        }
    }

}
