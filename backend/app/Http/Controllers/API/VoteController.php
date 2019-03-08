<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Vote;
use App\Models\Post;
use App\Models\Quote;
use App\Models\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVote;
use App\Http\Resources\VoteResource;
use App\Models\Traits\FindModelTrait;



class VoteController extends Controller
{
    use FindModelTrait;
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show']);
    }
    
    
    public function index(Request $request)
    {
        //
        $voted_model=$this->findModel(
            $request->votable_type,
            $request->votable_id,
            array('Post','Quote','Status')
        );
        if(empty($voted_model)){abort(410);}

        $votes=$voted_model->votes->whereNotIn('attitude',['downvote']);
        return response()->success([
            'votes' => VoteResource::collection($votes),
        ]);
    }

    
    public function create()
    {
        //
    }

    
    public function store(StoreVote $form)
    {
        //      
        $voted_model=$this->findModel(
            $form->votable_type,
            $form->votable_id,
            array('Post','Quote','Status')
        );
        if(empty($voted_model)){abort(410);} //检查被投票的对象是否存在

        $vote = $form->generateVote($voted_model);

        return response()->success(new VoteResource($vote));
             
        	
        
        	
    }

  
    public function show(Vote $vote)
    {
        //
        

    }

    
    public function edit(Vote $vote)
    {
        //
    }

   
    public function update(Request $request, Vote $vote)
    {
        //
    }

   
    public function destroy(Vote $vote)
    {
        //
    }
}
