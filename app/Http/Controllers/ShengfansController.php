<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Shengfan;
use Auth;
use Carbon\Carbon;

class ShengfansController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function vote_post(Post $post, Request $request)
    {  //是否投喂过
        $user = Auth::user();
        $data = [];
        $shengfan_num = request('num');
        if(($shengfan_num<-1)||($shengfan_num>10)){
            $data['danger']='抱歉，您输入的剩饭数目不在-1～10之间';
        }else{
            if ($post->shengfan_voted($user)){
                $data['info']='抱歉，您已为该回帖投过剩饭';
            }else{
                if ($user->shengfan < $shengfan_num){
                    $data['warning']='抱歉，您的剩饭不足';
                }else{//数目合适，没投过，剩饭足够
                    $data = DB::transaction(function() use($post, $shengfan_num, $user, $data){
                        $shengfan = Shengfan::create([
                            'user_id' => $user->id,
                            'shengfan_num' => $shengfan_num,
                            'post_id' => $post->id,
                        ]);
                        $user->update(['lastresponded_at' => Carbon::now()]);
                        $user->decrement('shengfan', $shengfan_num);//动作发起者自己减少对应剩饭
                        $user->increment('jifen', $shengfan_num);//但是增加自己的积分
                        $user->increment('experience_points', $shengfan_num);//但是增加自己的经验

                        $thread = $post->thread;
                        if($thread->post_id=$post->id){//确认是给一个主题扔剩饭
                            $thread->increment('shengfan', $shengfan_num);//每当主题被人扔剩饭，主题得同等剩饭
                            $author = $thread->creator;
                            $author->increment('shengfan', $shengfan_num);//每当主题被人扔剩饭，自己得等量剩饭
                            $author->increment('jifen', 1);//每当主题被人扔剩饭，自己得等量积分
                            $author->increment('experience_points', $shengfan_num);//得经验
                            $data["success"]="您已成功投掷剩饭~";
                            $data["shengfan"]=$thread->shengfan;
                            return $data;
                        }
                    });
                }
            }
        }
        return $data;
    }
}
