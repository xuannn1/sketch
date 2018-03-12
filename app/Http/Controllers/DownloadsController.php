<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Helpers\Helper;
use App\Label;
use App\Thread;
use App\Book;
use App\Post;
use App\Chapter;
use App\Tag;
use Carbon\Carbon;
use App\Tongren;
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
      $this->middleware('auth')->except(['index']);
    }
    public function index()
    {
        //
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
        $string = str_replace("<p>", "<p>    ", $string);
      }
      $string = Helper::htmltotext($string);
      return $string;
    }
    public function generate_thread_text(Thread $thread)
    {
      $posts = Post::where([
        ['thread_id', '=', $thread->id],
        ['id', '<>', $thread->post_id]
        ])
        ->with(['owner','reply_to_post.owner','chapter','comments.owner'])
        ->oldest()
        ->get();
      $thread->load(['channel','creator', 'tags', 'label', 'mainpost.comments.owner']);
      $txt = 'Downloaded from http://sosad.fun by Username:'.Auth::user()->name.' UserID:'.Auth::user()->id.' at UTC+8 '.Carbon::now(8)."\n";
      $txt .= "标题：".$thread->title."\n";
      $txt .= "简介：".$thread->brief."\n";
      $txt .= "发帖人：";
      if($thread->anonymous){$txt.=$thread->majia;}else{$txt.=$thread->creator->name;}
      $txt .= " at ".Carbon::parse($thread->created_at)->setTimezone(8);
      if($thread->created_at < $thread->edited_at){
        $txt.= "/".Carbon::parse($thread->edited_at)->setTimezone(8);
      }
      $txt .="\n正文：\n".$this->process_text($thread->body,$thread->mainpost->markdown,$thread->mainpost->indentation);
      $postcomments = $thread->mainpost->comments;
      foreach($postcomments as $k => $postcomment){
        $txt .= "主楼点评".($k+1).": ";
        if($postcomment->anonymous){$txt.=$postcomment->majia;}else{$txt.=$postcomment->owner->name;}
        $txt .= ' '.Carbon::parse($postcomment->created_at)->setTimezone(8)."\n";
        $txt .= $postcomment->body."\n";
      }
      $txt .= "\n";
      foreach($posts as $i=>$post){
        $txt.="回帖".($i+1).": ";
        if($post->anonymous){$txt.=$post->majia;}else{$txt.=$post->owner->name;}
        $txt .= " ".Carbon::parse($post->created_at)->setTimezone(8);
        if($post->created_at < $post->edited_at){
          $txt .= "/".Carbon::parse($post->edited_at)->setTimezone(8);
        }
        $txt .= "\n";
        if($post->title){$txt .= $post->title."\n";}
        $txt .= $this->process_text($post->body,$post->markdown,$post->indentation);
        foreach($post->comments as $k => $postcomment){
          $txt .= "回帖".($i+1)."点评".($k+1).": ";
          if($postcomment->anonymous){$txt.=$postcomment->majia;}else{$txt.=$postcomment->owner->name;}
          $txt .= " ".Carbon::parse($postcomment->created_at)->setTimezone(8)."\n";
          $txt .= $postcomment->body."\n";
        }
        $txt .= "\n";
      }
      return $txt;
     }
      public function generate_book_noreview_text(Thread $thread)
      {
        $book = $thread->book;
        $chapters = $book->chapters;
        $chapters->load(['mainpost']);
        $thread->load(['creator', 'tags', 'label']);
        $book_info = Config::get('constants.book_info');
        $txt = 'Downloaded from http://sosad.fun by Username:'.Auth::user()->name.' UserID:'.Auth::user()->id.' at UTC+8 '.Carbon::now(8)."\n";
        $txt .= "标题：".$thread->title."\n";
        $txt .= "简介：".$thread->brief."\n";
        $txt .= "作者：";
        if($thread->anonymous){$txt.=$thread->majia;}else{$txt.=$thread->creator->name;}
        $txt .= " at ".Carbon::parse($thread->created_at)->setTimezone(8);
        if($thread->created_at < $thread->edited_at){
          $txt.= "/".Carbon::parse($thread->edited_at)->setTimezone(8);
        }
        $txt .= "\n";
        $txt .= "图书信息：".$book_info['originality_info'][$book->original].'-'.$book_info['book_lenth_info'][$book->book_length].'-'.$book_info['book_status_info'][$book->book_status].'-'.$book_info['sexual_orientation_info'][$book->sexual_orientation];
        if($thread->bianyuan){$txt .= "|边缘";}
        $txt .= '|'.$thread->label->labelname;
        foreach ($thread->tags as $tag){
          $txt .= '-'.$tag->tagname;
        }
        $txt .="\n文案：\n".$this->process_text($thread->body,$thread->mainpost->markdown,$thread->mainpost->indentation)."\n";

        foreach($chapters as $i=>$chapter){
          $txt .= ($i+1).'.'.$chapter->title."\n";//章节名
          $txt .= Carbon::parse($chapter->created_at)->setTimezone(8);
          if($chapter->created_at < $chapter->edited_at){
            $txt.= "/".Carbon::parse($chapter->edited_at)->setTimezone(8);
          }
          $txt .= "\n";
          if($chapter->mainpost->title){$txt .= $chapter->mainpost->title."\n";}
          if($chapter->mainpost->body){$txt .= $this->process_text($chapter->mainpost->body,$chapter->mainpost->markdown,$chapter->mainpost->indentation)."\n";}
          if($chapter->annotation){$txt .= "备注：".$this->process_text($chapter->mainpost->annotation,1,0);}
          $txt .="\n";
        }
        return $txt;
      }
     public function thread_txt(Thread $thread)
     {
        $user = Auth::user();
        if (($user->id!=$thread->user_id)||(!$user->admin)) {//假如并非本人主题，登陆用户也不是管理员，首先看主人是否允许开放下载
          if (!$thread->download_as_thread){
            return redirect()->back()->with("danger","作者并未开放下载");
          }else{
            if($user->user_level>0){
              if ($thread->book_id > 0){//图书的下载需要更多剩饭咸鱼
                if (($user->shengfan > 5)&&($user->xianyu > 1)){
                  $user->decrement('shengfan',5);
                  $user->decrement('xianyu',1);
                }else{
                  return redirect()->back()->with("danger","您的剩饭与咸鱼不够，不能下载");
                }
              }else{//下载讨论帖需要的剩饭稍微少一些
                if ($user->shengfan > 2){
                  $user->decrement('shengfan',2);
                }else{
                  return redirect()->back()->with("danger","您的剩饭与咸鱼不够，不能下载");
                }
              }
            }else{
              return redirect()->back()->with("danger","您的用户等级不够，不能下载");
            }
          }
        }
        $thread->increment('downloaded');//reward part
        $author = $thread->creator;
        $author->increment('shengfan',5);
        $author->increment('jifen',5);
        $author->increment('xianyu',1);

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
        if (($user->id!=$thread->user_id)||(!$user->admin)) {//假如并非本人主题，登陆用户也不是管理员，首先看主人是否允许开放下载
          if (!$thread->download_as_book){
            return redirect()->back()->with("danger","作者并未开放下载");
          }else{
            if($user->user_level>4){
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
        $thread->increment('downloaded');
        $author = $thread->creator;
        $author->increment('shengfan',5);
        $author->increment('jifen',5);
        $author->increment('xianyu',1);

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
