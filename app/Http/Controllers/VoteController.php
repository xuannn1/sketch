<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vote;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVote;
use App\Sosadfun\Traits\FindModelTrait;
use CacheUser;
use Cache;
use Auth;
use Carbon;

class VoteController extends Controller
{
    use FindModelTrait;

    public function __construct()
    {
        $this->middleware('auth');
    }

    private function findVotableModel($request)
    {
        if (!array_key_exists('votable_type', $request)
        || !array_key_exists('votable_id', $request)){
            return false;
        }
        return $this->findModel(
            $request['votable_type'],
            $request['votable_id'],
            array('post','quote','status','thread')
        );
    }

    private function findVoteRecord($request)
    {
        return $request['votable_type'].$request['votable_id'].$request['attitude_type'];
    }

    public function index(Request $request)
    {
        $type = $request->votable_type;
        $model=$this->findVotableModel($request->all());
        if(!$model){abort(404);}

        $page = is_numeric($request->page)? 'P'.$request->page:'P1';
        $votes = Cache::remember('upvoteindex.'.$request->votable_type.$request->votable_id.$page, 15, function () use($request){
            $votes = Vote::with('author')
            ->withType($request->votable_type)
            ->withId($request->votable_id)
            ->withAttitude('upvote')
            ->orderBy('created_at','desc')
            ->paginate(config('preference.votes_per_page'))
            ->appends($request->only(['votable_type','votable_id']));
            return $votes;
        });

        return view('votes.index', compact('type', 'model', 'votes'));
    }

    public function received(Request $request)
    {
        $user = CacheUser::Auser();
        $info = CacheUser::Ainfo();
        $info->clear_column('upvote_reminders');
        $votes = Vote::with('votable','author')
        ->where('receiver_id',$user->id)
        ->withAttitude('upvote')
        ->orderBy('created_at','desc')
        ->paginate(config('preference.votes_per_page'));
        return view('votes.index_received', compact('user', 'info', 'votes'))->with('show_vote_tab','received');

    }

    public function sent(Request $request)
    {
        $user = CacheUser::Auser();
        $info = CacheUser::Ainfo();
        $votes = Vote::with('votable','author')
        ->where('user_id',$user->id)
        ->orderBy('created_at','desc')
        ->paginate(config('preference.votes_per_page'));
        return view('votes.index_sent', compact('user', 'info', 'votes'))->with('show_vote_tab','sent');
    }

    public function store(StoreVote $form)
    {
        $voted_model=$this->findVotableModel($form->all());
        if(empty($voted_model)){
            return [
                'danger' => '内容已删除或不存在',
            ];
        }

        if(Cache::has('VoteRecord'.Auth::id().$this->findVoteRecord($form->all()))){
            return [
                'danger' => '重复评票',
            ];
		}

        $vote = $form->generateVote($voted_model);

        Cache::put('VoteRecord'.Auth::id().$this->findVoteRecord($form->all()),1,1440);

        return [
            'success' => '已成功评票'
        ];
    }


    public function destroy(Vote $vote)
    {

        if(!$vote){
            return [
                'danger' => '内容已删除或不存在'
            ];
        }
        $vote_id = $vote->id;
        if($vote->user_id!=auth()->id()){
            return [
                'danger' => "不能删除他人的打赏"
            ];
        }
        $model=$vote->votable;
        if($model){
            $model->type_value_change($vote->attitude_type.'_count', -1);
        }
        $vote->delete();

        return [
            'success' => "成功删除已有打赏",
            'vote_id' => $vote_id,
        ];
    }
}
