<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use GrahamCampbell\Markdown\Facades\Markdown;

use Illuminate\Support\Facades\DB;
use App\Helpers\Helper;
use App\Models\Label;
use App\Models\Thread;
use App\Models\Book;
use App\Models\Post;
use App\Models\Chapter;
use App\Models\Tag;
use Carbon\Carbon;
use App\Models\Tongren;
use App\Models\Download;
use Auth;

class DownloadsController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Thread $thread)
    {
        return view('downloads.index', compact('thread'));
    }
    public function print_book_info($thread)
    {
        $book_info = config('constants.book_info');
        $book = $thread->book;
        $txt = "标题：".Helper::convert_to_title($thread->title)."\n";
        $txt .= "简介：".Helper::convert_to_public($thread->brief)."\n";
        $txt .= "作者：";
        if($thread->anonymous){$txt.=($thread->majia ?? "匿名咸鱼");}else{$txt.=$thread->creator->name;}
        $txt .= " at ".Carbon::parse($thread->created_at)->setTimezone(8);
        if($thread->created_at < $thread->edited_at){
            $txt.= "/".Carbon::parse($thread->edited_at)->setTimezone(8);
        }
        $txt .= "\n";
        $txt .= "图书信息：".$book_info['originality_info'][2-$thread->channel_id].'-'.$book_info['book_lenth_info'][$book->book_length].'-'.$book_info['book_status_info'][$book->book_status].'-'.$book_info['sexual_orientation_info'][$book->sexual_orientation];
        if($thread->bianyuan){$txt .= "|边缘";}
        $txt .= '|'.$thread->label->labelname;
        foreach ($thread->tags as $tag){
            $txt .= '-'.$tag->tagname;
        }
        $txt .="\n文案：\n".$this->process_text($thread->body,$thread->mainpost->markdown,$thread->mainpost->indentation)."\n";
        return $txt;
    }

    public function print_thread_info($thread)
    {
        $txt = "标题：".Helper::convert_to_title($thread->title)."\n";
        $txt .= "简介：".Helper::convert_to_public($thread->brief)."\n";
        $txt .= "发帖人：";
        if($thread->anonymous){$txt.=($thread->majia ?? "匿名咸鱼");}else{$txt.=$thread->creator->name;}
        $txt .= " at ".Carbon::parse($thread->created_at)->setTimezone(8);
        if($thread->created_at < $thread->edited_at){
            $txt.= "/".Carbon::parse($thread->edited_at)->setTimezone(8);
        }
        $txt .="\n正文：\n".$this->process_text($thread->body,$thread->mainpost->markdown,$thread->mainpost->indentation);
        return $txt;
    }
    public function reply_to_sth($post)
    {
        $txt = "";
        if($post->reply_to_post_id!=0){
            $txt .= "回复".($post->reply_to_post->anonymous ? ($post->reply_to_post->majia ?? '匿名咸鱼') : $post->reply_to_post->owner->name).Helper::trimtext($post->reply_to_post->title . $post->reply_to_post->body, 20)."\n";
        }elseif(($post->chapter_id!=0)&&(!$post->maintext)&&($post->chapter->mainpost->id>0)){
            $txt .= "评论".Helper::trimtext( $post->chapter->title . $post->chapter->mainpost->title . $post->chapter->mainpost->body , 20)."\n";
        }
        return $txt;
    }
    public function add_download_info($thread){//添加下载声明
        $txt = 'Downloaded from http://sosad.fun by '.Auth::user()->name.' '.Auth::user()->id.' at UTC+8 '.Carbon::now(8)."\n";
        $txt .= "仅供个人备份使用，请勿私自传播，书籍/文章所有权利属于原作者，个人评论如非特别声明，遵循知识共享CC-BY-NC-SA。For personal backup only. All rights of independent articles reserved to its author. Except where otherwise noted, comments are distributed under Creative Commons CC-BY-NC-SA.\n";
        return $txt;
    }
    public function process_text($string,$markdown,$indentation)
    {
        if($markdown){
            $string = Helper::sosadMarkdown($string);
        }else{
            $string = Helper::wrapParagraphs($string);
        }
        if($indentation)
        {
            $string = str_replace("<p>", "<p>　　", $string);
        }
        $string = Helper::htmltotext($string);
        return $string;
    }
    public function generate_thread_text(Thread $thread)//临时制作输出文件
    {
        $posts = Post::where([
            ['thread_id', '=', $thread->id],
            ['id', '<>', $thread->post_id]
        ])
        ->with(['owner','reply_to_post.owner','chapter','comments.owner'])
        ->oldest()
        ->get();
        $thread->load(['channel','creator', 'tags', 'label', 'mainpost.comments.owner']);
        $txt = $this->add_download_info($thread);
        if($thread->book_id>0){
            $txt .=$this->print_book_info($thread);
        }else{
            $txt .=$this->print_thread_info($thread);
        }
        $postcomments = $thread->mainpost->comments;
        foreach($postcomments as $k => $postcomment){
            $txt .= "主楼点评".($k+1).": ";
            if($postcomment->anonymous){$txt.=($postcomment->majia ?? "匿名咸鱼");}else{$txt.=$postcomment->owner->name;}
            $txt .= ' '.Carbon::parse($postcomment->created_at)->setTimezone(8)."\n";
            $txt .= $postcomment->body."\n";
        }
        $txt .= "\n";
        foreach($posts as $i=>$post){
            $txt.="回帖".($i+1).": ";

            if($post->maintext){
                if($thread->anonymous){$txt.=($thread->majia ?? "匿名咸鱼");}else{$txt.=$thread->creator->name;}
            }else{
                if($post->anonymous){$txt.=($post->majia ?? "匿名咸鱼");}else{$txt.=$post->owner->name;}
            }
            $txt .= " ".Carbon::parse($post->created_at)->setTimezone(8);
            if($post->created_at < $post->edited_at){
                $txt .= "/".Carbon::parse($post->edited_at)->setTimezone(8);
            }
            $txt .= "\n";
            $txt .= $this->reply_to_sth($post);
            if($post->maintext){$txt .= $post->chapter->title."\n";}
            if($post->title){$txt .= $post->title."\n";}
            $txt .= $this->process_text($post->body,$post->markdown,$post->indentation);
            if($post->chapter->annotation){$txt .= "备注".$this->process_text($post->chapter->annotation,0,0);}

            foreach($post->comments as $k => $postcomment){
                $txt .= "回帖".($i+1)."点评".($k+1).": ";
                if($postcomment->anonymous){$txt.=($postcomment->majia ?? "匿名咸鱼");}else{$txt.=$postcomment->owner->name;}
                $txt .= " ".Carbon::parse($postcomment->created_at)->setTimezone(8)."\n";
                $txt .= $postcomment->body."\n";
            }
            $txt .= "\n";
        }
        $txt .= $this->add_download_info($thread);
        $txt = str_replace("\n","\r\n",$txt);
        return $txt;
    }
    public function generate_book_noreview_text(Thread $thread)
    {
        $book = $thread->book;
        $chapters = $book->chapters;
        $chapters->load(['mainpost']);
        $thread->load(['creator', 'tags', 'label']);
        $book_info = config('constants.book_info');
        $txt = $this->add_download_info($thread);
        $txt .=$this->print_book_info($thread);
        foreach($chapters as $i=>$chapter){
            $txt .= ($i+1).'.'.Helper::convert_to_public($chapter->title)."\n";//章节名
            $txt .= Carbon::parse($chapter->created_at)->setTimezone(8);
            if($chapter->created_at < $chapter->edited_at){
                $txt.= "/".Carbon::parse($chapter->edited_at)->setTimezone(8);
            }
            $txt .= "\n";
            if($chapter->mainpost->title){$txt .= $chapter->mainpost->title."\n";}
            if($chapter->mainpost->body){$txt .= $this->process_text($chapter->mainpost->body,$chapter->mainpost->markdown,$chapter->mainpost->indentation)."\n";}
            if($chapter->annotation){$txt .= "备注：".$this->process_text($chapter->mainpost->annotation,0,0);}
            $txt .="\n";
        }
        $txt .= $this->add_download_info($thread);//添加下载备注
        $txt = str_replace("\n","\r\n",$txt);
        return $txt;
    }
    public function thread_txt(Thread $thread)
    {
        $user = Auth::user();
        if (($user->id!=$thread->user_id)||(!$user->admin)) {//假如并非本人主题，登陆用户也不是管理员，首先看主人是否允许开放下载
            if ((!$thread->public)||(!$thread->download_as_thread)){
                return redirect()->back()->with("danger","作者并未开放下载");
            }else{
                if($user->user_level>0){
                    if ($thread->channel->channel_state < 10){
                        if ($thread->book_id > 0){//图书的下载需要更多剩饭咸鱼
                            if (($user->user_level>=2)&&($user->shengfan > 10)&&($user->xianyu > 2)){
                                $user->decrement('shengfan',10);
                                $user->decrement('xianyu',2);
                            }else{
                                return redirect()->back()->with("danger","您的等级或剩饭与咸鱼不够，不能下载");
                            }
                        }else{//下载讨论帖需要的剩饭稍微少一些
                            if ($user->shengfan > 5){
                                $user->decrement('shengfan',5);
                            }else{
                                return redirect()->back()->with("danger","您的剩饭与咸鱼不够，不能下载");
                            }
                        }
                    }else{
                        return redirect()->back()->with("danger","特殊板块,不开放非本人主题贴下载");
                    }
                }else{
                    return redirect()->back()->with("danger","您的用户等级不够，不能下载");
                }
            }
        }

        DB::transaction(function () use($user, $thread){
            if($thread->user_id!=$user->id){//并非作者本人下载，奖励部分
                $thread->creator->reward('book_downloaded_as_thread');
                $thread->increment('downloaded');
                if ($thread->book_id>0){$format = 1;}else{$format = 0;}
                $download = Download::create([
                    'user_id' => $user->id,
                    'thread_id' => $thread->id,
                    'format' => $format,
                ]);
            }
        });


        $txt = $this->generate_thread_text($thread);//制作所需要的文档

        $response = new StreamedResponse();
        $response->setCallBack(function () use($txt) {
            echo $txt;
        });
        $disposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'thread'.$thread->id.'.txt');
        $response->headers->set('Content-Disposition', $disposition);
        return $response;
    }
    public function book_noreview_text(Thread $thread)
    {
        $user = Auth::user();
        if (($user->id!=$thread->user_id)&&(!$user->admin)){//假如并非本人主题，登陆用户也不是管理员，首先看主人是否允许开放下载
            if ((!$thread->public)||(!$thread->download_as_book)){
                return redirect()->back()->with("danger","作者并未开放下载");
            }else{
                if($user->user_level>2){
                    if (($user->shengfan > 10)&&($user->xianyu > 2)){
                        $user->decrement('shengfan',10);
                        $user->decrement('xianyu',2);
                    }else{
                        return redirect()->back()->with("danger","您的剩饭与咸鱼不够，不能下载");
                    }
                }else{
                    return redirect()->back()->with("danger","您的用户等级不够，不能下载");
                }
            }
        }
        if($thread->user_id!=$user->id){//并非作者本人下载，奖励部分
            DB::transaction(function () use($user, $thread){
                $thread->creator->reward('book_downloaded_as_book');
                $thread->increment('downloaded');
            });
        }
        $download = Download::create([
            'user_id' => $user->id,
            'thread_id' => $thread->id,
            'format' => 3,
        ]);
        $txt = $this->generate_book_noreview_text($thread);//制作所需要的下载文档
        $response = new StreamedResponse();
        $response->setCallBack(function () use($txt) {
            echo $txt;
        });
        $disposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'book'.$thread->book_id.'.txt');
        $response->headers->set('Content-Disposition', $disposition);
        return $response;
    }


}
