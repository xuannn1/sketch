@extends('layouts.default')
@section('title', '打赏回帖')

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <!-- 首页／版块／导航 -->
        <div class="">
            <a type="btn btn-danger sosad-button" href="{{ route('home') }}"><span class="glyphicon glyphicon-home"></span><span>首页</span></a>
            /
            <a href="{{ route('channel.show', $thread->channel()->id) }}">{{ $thread->channel()->channel_name }}</a>
            /
            <a href="{{ route('thread.show',$thread->id) }}">{{ $thread->title }}</a>
            /
            <a href="{{ route('post.show',$post->id) }}">回帖{{$post->id}}</a>
            / 打赏
        </div>

        <div class="panel panel-default" id = "post{{ $post->id }}">
            <h3>打赏这个回帖：</h3>
            <div class="panel-body post-body">
                @if((($post->is_bianyuan)||($thread->is_bianyuan))&&(!Auth::check()||(Auth::check()&&(Auth::user()->level < 1))))
                <div class="text-center">
                    <h6 class="display-4 grayout"><a href="{{ route('login') }}">本内容为隐藏格式，只对1级以上注册用户开放，请登录或升级后查看</a></h6>
                </div>
                @else
                <div class="main-text {{ $post->use_indentation? 'indentation':'' }}">
                    @if($post->title)
                    <div class="text-center">
                        <strong>{{ $post->title }}</strong>
                    </div>
                    @endif
                    <span id="full{{$post->id}}" class="hidden">
                        @if($post->use_markdown)
                        {!! StringProcess::sosadMarkdown($post->body) !!}
                        @else
                        {!! StringProcess::wrapParagraphs($post->body) !!}
                        @endif
                    </span>
                    <span id="abbreviated{{$post->id}}">
                        {!! StringProcess::trimtext($post->body,70) !!}
                    </span>
                    <a type="button" name="button" id="expand{{$post->id}}" onclick="expanditem('{{$post->id}}')">展开</a>
                </div>
                @endif
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-body">
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
                            <label><input type="text" style="width: 80px" name="reward_value" value="1">数额(1-100)</label>
                        </div>
                        <hr>
                        <label><input name="rewardable_type" value="post" class="hidden"></label>
                        <label><input name="rewardable_id" value="{{$post->id}}" class="hidden"></label>
                        <div class="text-right">
                            <button type="submit" class="btn btn-lg btn-primary sosad-button">确认打赏这个回帖</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
