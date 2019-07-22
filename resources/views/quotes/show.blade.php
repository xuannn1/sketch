@extends('layouts.default')
@section('title', '题头' )

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <!-- 首页／版块／导航 -->
        <div class="">
            <a type="btn btn-danger sosad-button" href="{{ route('home') }}"><span class="glyphicon glyphicon-home"></span><span>首页</span></a>
            /
            <a href="{{ route('quote.index') }}">全站题头</a>
            /
            <a href="{{ route('quote.show', $quote->id) }}">第{{$quote->id}}号题头</a>
        </div>

        <!-- 展示一个题头 -->
        <div class="panel panel-default" id = "quote{{ $quote->id }}">
            <div class="panel-body text-center">
                <!-- 题头正文 -->
                <h1>{{ $quote->body }}</h1>
                <!-- 作者是谁 -->
                <div class="text-center">
                    ——
                    @if($quote->author)
                    @if ($quote->is_anonymous)
                        <span>{{ $quote->majia ?? '匿名咸鱼'}}</span>
                        @if((Auth::check()&&(Auth::user()->isAdmin())))
                        <span class="admin-anonymous"><a href="{{ route('user.show', $quote->user_id) }}">{{ $quote->author->name }}</a></span>
                        @endif
                    @else
                        <a href="{{ route('user.show', $quote->user_id) }}">
                            @if($quote->author->title&&$quote->author->title->name)
                            <span class="maintitle title-{{$quote->author->title->style_id}}">{{ $quote->author->title->name }}</span>
                            @endif
                            {{ $quote->author->name }}
                        </a>
                    @endif
                    @endif
                </div>
                <div class="text-center">
                    <span class="smaller-20 grayout">
                        发表于{{ $quote->created_at }}
                    </span>
                </div>
                <div class="text-center">
                    <span >
                        咸鱼：{{ $quote->fish }}
                    </span>
                </div>
                <br>
                @if((Auth::check())&&(Auth::user()->isAdmin()))
                <!-- 管理专区 -->
                <div class="row">
                    <div class="row quotebutton{{$quote->id}}">
                        <div class="col-xs-4 text-right">
                            <button class="btn btn-md btn-success cancel-button approvebutton{{$quote->id}} {{$quote->reviewed? 'hidden':''}}"  type="button" name="button" onClick="review_quote({{$quote->id}},'approve')">对外显示<i class="fa fa-check" aria-hidden="true"></i></button>
                        </div>
                        <div class="col-xs-4 text-center">
                            <button class="btn btn-md btn-info cancel-button togglebutton{{$quote->id}} {{$quote->reviewed? '':'hidden'}}"  type="button" name="button" onClick="reset_review_button({{$quote->id}})">重新审核</button>

                        </div>
                        <div class="col-xs-4 text-left">
                            <button class="btn btn-md  btn-danger cancel-button disapprovebutton{{$quote->id}} {{$quote->reviewed? 'hidden':''}}"  type="button" name="button" onClick="review_quote({{$quote->id}},'disapprove')">不显示<i class="fa fa-times" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </div>
                @endif

                @if(Auth::check())
                <!-- 打赏、评票行为 -->
                <div class="text-center">
                    <span><a href="#" data-id="{{$quote->id}}" data-toggle="modal" data-target="#TriggerQuoteReward{{ $quote->id }}" class="btn btn-info  btn-lg btn-block sosad-button">打赏</a></span>
                </div>
                <div class="modal fade" id="TriggerQuoteReward{{ $quote->id }}" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('reward.store')}}" method="POST">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <div class="">
                                        <label><input type="radio" name="reward_type" value="salt">盐粒(余额{{$info->salt}})</label>
                                    </div>
                                    <div class="">
                                        <label><input type="radio" name="reward_type" value="fish" checked>咸鱼(余额{{$info->fish}})</label>
                                    </div>
                                    <div class="">
                                        <label><input type="radio" name="reward_type" value="ham">火腿(余额{{$info->ham}})</label>
                                    </div>
                                    <hr>
                                    <div class="">
                                        <label><input type="text" style="width: 40px" name="reward_value" value="1">数额</label>
                                    </div>
                                    <hr>
                                    <label><input name="rewardable_type" value="quote" class="hidden"></label>
                                    <label><input name="rewardable_id" value="{{$quote->id}}" class="hidden"></label>
                                    <div class="text-right">
                                        <button type="submit" class="btn btn-md btn-primary sosad-button">打赏</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="panel-footer">
                <div class="font-4">
                    审核情况：{{$quote->reviewed?'已审核':'未审核'}}，{{$quote->approved?'已通过':'未通过'}}
                </div>
                <div class="font-4">
                    <span>新鲜打赏：</span>
                </div>
                @include('rewards._brief_rewards')
                <a href="{{ route('reward.index', ['rewardable_type'=>'quote', 'rewardable_id'=>$quote->id]) }}" class="pull-right">>>本题头的全部打赏</a>
                <br>
                <br>
            </div>
        </div>
    </div>
</div>
@stop
