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
            array('Post','Quote','Status')
        );
    }

    public function index(Request $request)
    {
        // TODO: 目前只给出一个物品被vote的结果，以后应允许管理员查看是谁提交了这些vote，也允许用户查看所有upvote的提供人。
        $voted_model=$this->findVotableModel($request->all());
        if(empty($voted_model)){abort(404);}

        $votes=$voted_model->votes;
        return response()->success([
            'votes' => VoteResource::collection($votes),
        ]);
    }

    public function store(StoreVote $form)
    {
        //
        $voted_model=$this->findVotableModel($form->all());
        if(empty($voted_model)){abort(404);} //检查被投票的对象是否存在

        $vote = $form->generateVote($voted_model);
        return response()->success(new VoteResource($vote));
    }


    public function destroy(Request $request)
    {
        // TODO：应该提供删除vote的方法：找到，然后删掉
        if($request->user_id!=auth('api')->id()){abort(403);}
        $count = $request->attitude.'_count';
        
        $query = Vote::where('user_id',$request->user_id)
            ->where('votable_type',$request->votable_type)
            ->where('votable_id', $request->votable_id)
            ->where('attitude',$request->attitude);
        $vote = $query->first();
        $query->delete();
        
        UserInfo::where('user_id',$vote->votable->user_id)->decrement($count);

        return response()->success('deleted');
    }
}
