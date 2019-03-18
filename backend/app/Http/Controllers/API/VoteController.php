<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Vote;
use App\Models\Post;
use App\Models\Quote;
use App\Models\Status;
use App\Models\UserInfo;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVote;
use App\Http\Resources\VoteResource;
use App\Sosadfun\Traits\FindModelTrait;
use App\Http\Resources\PaginateResource;


class VoteController extends Controller
{
    use FindModelTrait;
    public function __construct()
    {
        $this->middleware('auth:api');
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
            array('Post','Quote','Status','Thread')
        );
    }

    public function index(Request $request)
    {
        // TODO： 有待完成管理员可以看见各种投票内容的部分（怎样将管理员数据传递到resource内部去，需要研究一下）
        $voted_model=$this->findVotableModel($request->all());
        if(empty($voted_model)){abort(404);}

        $votes=$voted_model->votes()->paginate(config('constants.votes_per_page'));

        return response()->success([
            'votes' => VoteResource::collection($votes->load('author')),
            'paginate' => new PaginateResource($votes),
        ]);
    }

    public function store(StoreVote $form)
    {
        $voted_model=$this->findVotableModel($form->all());
        if(empty($voted_model)){abort(404);} //检查被投票的对象是否存在

        $vote = $form->generateVote($voted_model);
        // TODO：有待补充递增被投票用户的票数（分值系统一起做），同时应该给投票人以奖励
        return response()->success(new VoteResource($vote->load('author')));
    }


    public function destroy(Vote $vote)
    {
        if(!$vote){
            abort(404);
        }
        if($vote->user_id!=auth('api')->id()){
            abort(403);
        }
        $vote->delete();
        // TODO：有待补充递减被投票用户的票数（分值系统一起做），同时应该给投票人以惩罚
        return response()->success('deleted');
    }
}
