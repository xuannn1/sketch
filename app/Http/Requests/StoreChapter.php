<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use App\Helpers\Helper;
use App\Models\Book;
use App\Models\Thread;
use App\Models\Chapter;
use App\Models\Post;
use App\Models\Status;
use Carbon\Carbon;

class StoreChapter extends FormRequest
{
    /**
    * Determine if the user is authorized to make this request.
    *
    * @return bool
    */
    public function authorize(Book $book)
    {
        return true;
    }

    /**
    * Get the validation rules that apply to the request.
    *
    * @return array
    */
    public function rules()
    {
        return [
            'title' => 'required|string|max:35',
            'brief' => 'max:35',
            'body' => 'required|string|min:15',
            'annotation' => 'max:2000',
        ];
    }

    public function generateChapter(Book $book){
        $thread = $book->thread;
        //post data
        $post_data=$this->only('body');
        $post_data['title']=$this->brief;
        $post_data['user_ip']=$this->getClientIp();
        $post_data['user_id']=$thread->user_id;
        $post_data['thread_id']=$thread->id;
        $post_data['maintext']=true;
        $post_data['anonymous']=$thread->anonymous;
        $post_data['majia']=$thread->majia;
        $post_data['markdown']=$this->markdown? true: false;
        $post_data['indentation']=$this->indentation ? true:false;
        // chapter data
        $string = preg_replace('/[[:punct:]\s\n\t\r]/','',$post_data['body']);
        $chapter_data = $this->only('title','annotation');
        while(Helper::convert_to_title($chapter_data['title'])!=$chapter_data['title']){
           $chapter_data['title'] = Helper::convert_to_title($chapter_data['title']);
        }
        while(Helper::convert_to_title($post_data['title'])!=$post_data['title']){
           $post_data['title'] = Helper::convert_to_title($post_data['title']);
        }
        $chapter_data['characters'] = iconv_strlen($string, 'utf-8');
        $chapter_data['chapter_order'] = $book->max_chapter_order() ? $book->max_chapter_order()+1 : 1;
        $chapter_data['volumn_id'] = $book->recent_volumn() ? $book->recent_volumn->id: 0;
        $chapter_data['book_id'] = $book->id;
        $chapter_data['edited_at'] = Carbon::now();
        $sendstatuses = $this->sendstatuses? 1:0;
        if (!$this->isDuplicateChapter($post_data)){
            DB::transaction(function()use($post_data, $chapter_data, $book, $thread, $sendstatuses){
                $post = Post::create($post_data);
                $chapter_data['post_id']=$post->id;
                $chapter = Chapter::create($chapter_data);
                //count the total char for this book;
                $total_char = DB::table('chapters')
                ->join('posts','posts.id','=','chapters.post_id')
                ->where([
                    ['chapters.book_id','=',$book->id],
                    ['posts.deleted_at','=',NULL],
                ])
                ->sum('chapters.characters');
                $post->update(['chapter_id'=>$chapter->id]);

                if ($chapter_data['characters'] > config('constants.update_min')){
                    $book->update(['lastaddedchapter_at' => Carbon::now()]);
                    $thread->user->reward("regular_chapter");
                }else{
                    $thread->user->reward("short_chapter");
                }
                $thread->update([
                    'lastresponded_at' => Carbon::now(),
                    'last_post_id' => $post->id,
                ]);
                $thread->update_channel();
                $book->update([
                    'last_chapter_id' => $chapter->id,
                    'total_char' => $total_char
                ]);

                DB::table('collections')//告诉所有收藏本文章、愿意接受更新的读者, 这里发生了更新
                ->join('users','users.id','=','collections.user_id')
                ->where([['collections.thread_id','=',$thread->id],['collections.keep_updated','=',true],['collections.user_id','<>',auth()->id()]])
                ->update(['collections.updated'=>1,'users.collection_books_updated'=>DB::raw('users.collection_books_updated + 1')]);

                if(($sendstatuses)&&(!$thread->anonymous)){
                    Status::create([
                        'user_id' => auth()->id(),
                        'content' => '[url='.route('book.showchapter', $chapter->id).']'.'更新了《'.Helper::convert_to_title($thread->title).'》'.Helper::convert_to_public($chapter->title).'[/url]',
                    ]);
                }
            });
        }
    }

    public function isDuplicateChapter($data)
    {
        $last_post = Post::where('user_id', auth()->id())
        ->where('maintext',true)
        ->orderBy('id', 'desc')
        ->first();
        return count($last_post) && strcmp($last_post->body, $data['body']) === 0;
    }

    public function updateChapter(Chapter $chapter)
    {
        $book = $chapter->book;
        $thread = $book->thread;
        //post data
        $post_data=$this->only('body');
        $post_data['title']=$this->brief;
        $post_data['user_ip']=$this->getClientIp();
        $post_data['markdown']=$this->markdown? true: false;
        $post_data['indentation']=$this->indentation ? true:false;
        $post_data['edited_at'] = Carbon::now();
        // chapter data
        $string = preg_replace('/[[:punct:]\s\n\t\r]/','',$post_data['body']);
        $chapter_data = $this->only('title','annotation');
        while(Helper::convert_to_title($chapter_data['title'])!=$chapter_data['title']){
           $chapter_data['title'] = Helper::convert_to_title($chapter_data['title']);
        }
        while(Helper::convert_to_title($post_data['title'])!=$post_data['title']){
           $post_data['title'] = Helper::convert_to_title($post_data['title']);
        }
        $chapter_data['characters'] = iconv_strlen($string, 'utf-8');
        $chapter_data['edited_at'] = Carbon::now();

        $chapter->update($chapter_data);
        $post = $chapter->mainpost;
        $post->update($post_data);
        $total_char = DB::table('chapters')
        ->join('posts','posts.id','=','chapters.post_id')
        ->where([
            ['chapters.book_id','=',$book->id],
            ['posts.deleted_at','=',NULL],
        ])
        ->sum('chapters.characters');
        $book->update(['total_char' => $total_char]);
        return redirect()->route('book.showchapter', $chapter)->with("success", "您已成功修改章节");
    }
}
