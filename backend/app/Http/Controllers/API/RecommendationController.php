<?php

namespace App\Http\Controllers\API;

use App\Models\Recommendation;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRecommendation;
use App\Http\Requests\UpdateRecommendation;
use App\Http\Controllers\Controller;

class RecommendationController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index','show']);
    }
    public function index()
    {
        //
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        //
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(StoreRecommendation $form)
    {
        if(auth('api')->user()->canRecommend())
        $recommendation = $form->generateRecommendation();
        return response()->success($recommendation);
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Recommendation  $recommendation
    * @return \Illuminate\Http\Response
    */
    public function show(Recommendation $recommendation)
    {
        //
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Recommendation  $recommendation
    * @return \Illuminate\Http\Response
    */
    public function edit(Recommendation $recommendation)
    {
        //
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Recommendation  $recommendation
    * @return \Illuminate\Http\Response
    */
    public function update(Recommendation $recommendation, UpdateRecommendation $form)
    {
        //identity validation
        //是自己的recommendation，或者说是资深编辑/管理员
        $recommendation = $form->updateRecommendation($recommendation);
        return response()->success($recommendation);
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Recommendation  $recommendation
    * @return \Illuminate\Http\Response
    */
    public function destroy(Recommendation $recommendation)
    {
        //
    }
}
