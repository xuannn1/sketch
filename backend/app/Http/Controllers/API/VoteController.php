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


class VoteController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show']);
    }
    
    
    public function index(Request $request)
    {
        //
        $votable_type=$request->votable_type;
        $voted_model=$votable_type::where('id',$request->votable_id)->first();
        $votes=$voted_model->votes;
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
        //$validated = $form->validated();

        $vote = $form->generateVote();

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
