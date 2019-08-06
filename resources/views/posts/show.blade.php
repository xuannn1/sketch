@extends('layouts.default')
@section('title', $thread->title )

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <!-- 首页／版块／导航 -->
        <div class="">
            <a type="btn btn-lg btn-danger sosad-button" href="{{ route('home') }}"><span class="glyphicon glyphicon-home"></span><span>首页</span></a>
            /
            <a href="{{ route('channel.show', $thread->channel()->id) }}">{{ $thread->channel()->channel_name }}</a>
            /
            @if($thread->channel()->type==='book')
            <a href="{{ route('thread.show_profile',$thread->id) }}">{{ $thread->title }}</a>
            @else
            <a href="{{ route('thread.show',$thread->id) }}">{{ $thread->title }}</a>
            @endif
            /
            <a href="{{ route('post.show',$post->id) }}">{{ $post->title }}</a>
        </div>

        <!-- 展示一个帖子 -->
        <div class="panel panel-default" id = "post{{ $post->id }}">
            <div class="panel-body text-center">
                <div class="row">
                    <div class="col-xs-12">
                        <!-- 标题 -->
                        @if($post->title)
                        <div class="text-center">
                            <strong class="h3">{{ $post->title }}</strong>
                        </div>
                        @endif
                        @if($post->brief)
                        <div class="text-center">
                            <strong class="h5">{{ $post->brief }}</strong>
                        </div>
                        @endif
                        <!-- 作者名称 -->
                        <div>
                            @if($post->author)
                                @if($post->type==='post'||$post->type==='comment')
                                    @if ($post->is_anonymous)
                                    <span>{{ $post->majia ?? '匿名咸鱼'}}</span>
                                        @if((Auth::check()&&(Auth::user()->isAdmin())))
                                        <span class="admin-anonymous"><a href="{{ route('user.show', $post->user_id) }}">{{ $post->author->name }}</a></span>
                                        @endif
                                    @else
                                        <a href="{{ route('user.show', $post->user_id) }}">
                                            @if($post->author->title&&$post->author->title->name)
                                            <span class="maintitle title-{{$post->author->title->style_id}}">{{ $post->author->title->name }}</span>
                                            @endif
                                            {{ $post->author->name }}
                                        </a>
                                    @endif
                                @endif
                            @endif
                        </div>

                        <!-- 发表时间 -->
                        <div class="">
                            <span class="smaller-20 brief-0">
                                <p class="brief-0">{{ $post->created_at->setTimezone('Asia/Shanghai')  }}</p>
                                @if($post->created_at < $post->edited_at )
                                <p class="brief-0">{{ $post->edited_at->setTimezone('Asia/Shanghai')  }}</p>
                                @endif
                            </span>
                        </div>
                        @if((Auth::check())&&(Auth::user()->isAdmin()))
                        <!-- 管理专区 -->
                        <div class="">
                            <span>
                                <a href="{{route('admin.postform', $post->id)}}" class="btn btn-md btn-danger sosad-button admin-button"><span class="glyphicon glyphicon-user"></span>管理本帖</a>
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @if($post->type==="chapter"&&$post->chapter)
                @include('posts._previous_next_chapter_tab')
            @endif

            <?php $show_post_mode='book'; ?>
            @include('posts._post_body')

            @if($post->type==="chapter"&&$post->chapter)
                @include('posts._previous_next_chapter_tab')
            @endif

            @if(!empty($post->recent_rewards)&&count($post->recent_rewards)>0)
            <!-- 打赏列表  -->
            <div class="grayout h5 text-left">
                新鲜打赏：
                @foreach($post->recent_rewards as $reward)
                @if($reward->author)
                <a href="{{ route('user.show', $reward->user_id) }}">{{ $reward->author->name }},&nbsp;</a>
                @endif
                @endforeach
                &nbsp;&nbsp;<a href="{{route('reward.index', ['rewardable_type'=>'post', 'rewardable_id'=>$post->id])}}">&nbsp;&nbsp;>>全部打赏列表</a>
            </div>
            @endif
            @if(!empty($post->recent_upvotes)&&count($post->recent_upvotes)>0)
            <!-- 打赏列表  -->
            <div class="grayout h5 text-left">
                新鲜点赞：
                @foreach($post->recent_upvotes as $vote)
                @if($vote->author)
                <a href="{{ route('user.show', $vote->user_id) }}">{{ $vote->author->name }},&nbsp;</a>
                @endif
                @endforeach
                &nbsp;&nbsp;<a href="{{route('vote.index', ['votable_type'=>'post', 'votable_id'=>$post->id])}}">&nbsp;&nbsp;>>全部评票列表</a>
            </div>
            @endif
            @if(Auth::check())
            @include('posts._post_action')
            @endif
        </div>
        <div class="text-center h3">
            <a href="{{ route('thread.show', ['thread' => $thread->id, 'withReplyTo' => $post->id, 'withComponent'=>'include_comment']) }}" class="font-4 grayout">{{$post->reply_count}}条直接评论<<</a>&nbsp;&nbsp;&nbsp;&nbsp;<span>高赞评论</span>&nbsp;&nbsp;&nbsp;&nbsp;<a href="{{ route('thread.show', ['thread' => $thread->id, 'inComponent' => $post->in_component_id>0?$post->in_component_id:$post->id, 'withComponent'=>'include_comment']) }}" class="font-4 grayout">>>所有相关讨论</a>
        </div>
        <?php $replies = $post->new_replies ?>
        @include('posts._replies')
        @if(Auth::check())
        @include('threads._reply')
        @endif
    </div>
</div>
@stop
