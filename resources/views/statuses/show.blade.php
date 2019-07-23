@extends('layouts.default')
@section('title', '动态' )

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <!-- 首页／版块／导航 -->
        <div class="">
            <a type="btn btn-danger sosad-button" href="{{ route('home') }}"><span class="glyphicon glyphicon-home"></span><span>首页</span></a>
            /
            <a href="{{ route('status.index') }}">全站动态</a>
            /
            <a href="{{ route('status.show', $status->id) }}">第{{$status->id}}号动态</a>
        </div>

        <!-- 展示一个题头 -->
        <div class="panel panel-default" id = "status{{ $status->id }}">
            <div class="panel-body">

                <!-- 作者是谁 -->
                <div class="">
                    @if($status->author)
                    <a href="{{ route('user.show', $status->user_id) }}">
                        @if($status->author->title&&$status->author->title->name)
                        <span class="maintitle title-{{$status->author->title->style_id}}">{{ $status->author->title->name }}</span>
                        @endif
                        {{ $status->author->name }}
                    </a>
                    @endif
                    <span class="smaller-20 grayout">
                        {{ $status->created_at->setTimezone('Asia/Shanghai') }}
                    </span>
                    @if((Auth::check())&&(Auth::user()->isAdmin()))
                    <span>
                        <span><a href="#" data-id="{{$status->id}}" data-toggle="modal" data-target="#TriggerStatusAdministration{{ $status->id }}" class="btn btn-default btn-md admin-button">管理动态</a></span>
                        @include('admin._status_management_form')
                    </span>
                    @endif
                </div>

                <!-- 动态正文 -->
                <div class="font-4">
                    {!!  StringProcess::wrapParagraphs($status->body) !!}
                </div>
            </div>
            <div class="panel-footer">
                @if(!empty($status->recent_rewards)&&count($status->recent_rewards)>0)
                <!-- 打赏列表  -->
                <div class="grayout h5 text-left">
                    新鲜打赏：
                    @foreach($status->recent_rewards as $reward)
                    @if($reward->author)
                    <a href="{{ route('user.show', $reward->user_id) }}">{{ $reward->author->name }},&nbsp;</a>
                    @endif
                    @endforeach
                    &nbsp;&nbsp;<a href="{{route('reward.index', ['rewardable_type'=>'status', 'rewardable_id'=>$status->id])}}">&nbsp;&nbsp;>>全部打赏列表</a>
                </div>
                @endif
                @if(!empty($status->recent_upvotes)&&count($status->recent_upvotes)>0)
                <!-- 打赏列表  -->
                <div class="grayout h5 text-left">
                    新鲜点赞：
                    @foreach($status->recent_upvotes as $vote)
                    @if($vote->author)
                    <a href="{{ route('user.show', $vote->user_id) }}">{{ $vote->author->name }},&nbsp;</a>
                    @endif
                    @endforeach
                    &nbsp;&nbsp;<a href="{{route('vote.index', ['votable_type'=>'status', 'votable_id'=>$status->id])}}">&nbsp;&nbsp;>>全部评票列表</a>
                </div>
                @endif
                @if(Auth::check())
                <div class="text-right post-vote">
                    @if(Auth::user()->level >= 1)
                        <span class="voteposts"><button class="btn btn-default btn-md" data-id="{{$status->id}}" onclick="voteItem('status', {{$status->id}}, 'upvote')" ><span class="glyphicon glyphicon-heart"></span><span id="status{{$status->id}}upvote">{{ $status->upvote_count }}</span></button></span>
                    @endif
                    &nbsp;<span><a href="#" data-id="{{$status->id}}" data-toggle="modal" data-target="#TriggerStatusReward{{ $status->id }}" class="btn btn-default btn-md">打赏</a></span>
                    @include('statuses._reward_form')
                    @if((Auth::check())&&(Auth::id()==$status->user_id))
                        &nbsp;<a href="{{ route('status.destroy', $status->id) }}" class="btn btn-md btn-danger sosad-button" onclick="event.preventDefault(); document.getElementById('status-destroy-form').submit();">删除动态</a>
                        <form id="status-destroy-form" action="{{ route('status.destroy', $status->id) }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                            @method('DELETE')
                        </form>
                    @endif
                </div>
                @endif
            </div>

        </div>
    </div>
</div>
@stop
