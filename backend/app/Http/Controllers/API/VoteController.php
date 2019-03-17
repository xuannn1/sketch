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
        return response()->success(new VoteResource($vote));
    }


    public function destroy(Request $request)
    {
        $count = $request->attitude.'_count';

        $vote = Vote::where('user_id',auth('api')->id())
            ->where('votable_type',$request->votable_type)
            ->where('votable_id', $request->votable_id)
            ->where('attitude',$request->attitude)
            ->first();
        if(!$vote){
            abort(404);
        }
        $vote->delete();
        return response()->success('deleted');
    }
}
