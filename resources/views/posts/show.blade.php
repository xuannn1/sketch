@extends('layouts.default')
@section('title', $thread->title )

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <!-- 首页／版块／导航 -->
        <div class="h3">
            <a type="btn btn-danger sosad-button" href="{{ route('home') }}"><span class="glyphicon glyphicon-home"></span><span>首页</span></a>
            &nbsp;/&nbsp;
            <a href="{{ route('channel.show', $thread->channel()->id) }}">{{ $thread->channel()->channel_name }}</a>
            &nbsp;/&nbsp;
            <a href="{{ route('thread.show_profile',$thread->id) }}">{{ $thread->title }}</a>
            &nbsp;/&nbsp;
            <a href="{{ route('post.show',$post->id) }}">{{ $post->title }}</a>
        </div>

        <!-- 展示该主题下每一个帖子 -->
        <div class="panel panel-default" id = "post{{ $post->id }}">
            <div class="panel-body text-center">
                <div class="row">
                    <div class="col-xs-12">
                        <div>
                            <!-- 显示作者名称 -->
                            @if($post->author)
                                @if ($post->maintext)
                                    <span class="h2">作者</span>
                                @else
                                    @if ($post->anonymous)
                                    <span>{{ $post->majia ?? '匿名咸鱼'}}</span>
                                        @if((Auth::check()&&(Auth::user()->isAdmin())))
                                        <span class="admin-anonymous"><a href="{{ route('user.show', $post->user_id) }}">{{ $post->author->name }}</a></span>
                                        @endif
                                    @else
                                        <a href="{{ route('user.show', $post->user_id) }}">{{ $post->author->name }}</a>
                                    @endif
                                @endif
                            @endif
                        </div>
                        <div class="">
                            <!-- 发表时间 -->
                            <span class="smaller-20">
                                发表于 {{ $post->created_at->diffForHumans() }}
                                @if($post->created_at < $post->edited_at )
                                修改于 {{ $post->edited_at->diffForHumans() }}
                                @endif
                            </span>&nbsp;
                        </div>
                        <div class="">
                            @if((Auth::check())&&(Auth::user()->isAdmin()))
                            <span>
                                <a href="#" class="btn btn-md btn-danger sosad-button admin-button"><span class="glyphicon glyphicon-user"></span>管理本帖</a>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-body post-body">
                @if((($post->bianyuan)||($thread->bianyuan))&&(!Auth::check()||(Auth::check()&&(Auth::user()->level < 1))))
                <div class="text-center">
                    <h6 class="display-4 grayout"><a href="{{ route('login') }}">本内容为隐藏格式，只对1级以上注册用户开放，请登录或升级后查看</a></h6>
                </div>
                @else
                    <!-- 回复他人帖子的相关信息 -->
                    @if($post->reply_to_id!=0)
                        <div class="post-reply grayout">
                            回复&nbsp;<a href="{{ route('thread.showpost', $post->reply_to_id) }}">{{ $post->reply_to_brief }}</a>
                        </div>
                    @endif

                    <!-- 展示推荐书籍内情 -->
                    @if($post->type==='review'&&$post->review)
                        <div class="post-reply grayout">
                            @if($post->review->reviewee)
                            <a href="{{ route('thread.show_profile', $post->review->thread_id) }}">《{{ $post->review->reviewee->title }}》</a>
                            @endif

                            @for ($i = 0; $i < $post->review->rating; $i++)
                            @if($i%2!=0)
                            <i class="fa fa-star recommend-star" aria-hidden="true"></i>
                            @endif
                            @endfor

                            @if($post->review->rating>0&&$post->review->rating%2!=0)
                            <i class="fa fa-star-half-o recommend-star" aria-hidden="true"></i>
                            @endif
                            @if($post->review->recommend)
                            <span class="badge newchapter-badge badge-tag"><i class="fa fa-heartbeat" aria-hidden="true"></i>推荐</span>
                            @endif
                        </div>
                    @endif

                    <!-- 普通回帖展开 -->
                    <div class="main-text {{ $post->indentation? 'indentation':'' }}">
                        @if($post->title)
                        <div class="text-center">
                            <strong class="h3">{{ $post->title }}</strong>
                        </div>
                        @endif

                        @if($post->type==="chapter"&&$post->chapter&&$post->chapter->warning)
                        <div class="text-center grayout">
                            {{ $post->chapter->warning }}
                        </div>
                        <br>
                        @endif

                        @if($post->markdown)
                        {!! Helper::sosadMarkdown($post->body) !!}
                        @else
                        {!! Helper::wrapParagraphs($post->body) !!}
                        @endif
                        <br>
                        @if($post->type==="chapter"&&$post->chapter&&$post->chapter->annotation)
                        <div class="text-left grayout">
                            {!! Helper::sosadMarkdown($post->chapter->annotation) !!}
                        </div>
                        @endif
                    </div>

                @endif
            </div>

            @if(Auth::check())
            <div class="text-right post-vote">
                @if(Auth::user()->level >= 1)
                    <span class="voteposts"><button class="btn btn-default btn-md" data-id="{{$post->id}}"  id = "{{$post->id.'upvote'}}" onclick="vote_post({{$post->id}},'upvote')" ><span class="glyphicon glyphicon-heart">{{ $post->upvote_count }}</span></button></span>
                @endif
                @if((!$thread->locked)&&(!$thread->noreply)&&(!Auth::user()->no_posting)&&(!$post->is_folded)&&(Auth::user()->level >= 2))
                    <span ><a href = "#replyToThread" class="btn btn-default btn-md" onclick="replytopost({{ $post->id }}, '{{ StringProcess::trimtext($post->title.$post->brief, 40) }}')"><span class="glyphicon glyphicon-comment">{{ $post->reply_count }}</span></a></span>
                @endif

                @if(($post->user_id===Auth::id())&&(!$thread->locked)&&(!$post->is_folded)&&($thread->channel()->allow_edit))
                    <span><a class="btn btn-danger sosad-button btn-md" href="{{ route('post.edit', $post->id) }}">编辑</a></span>
                @endif

                @if($post->type==="chapter"&&$post->chapter)
                <div class="container-fluid">
                    <br>
                    <div class="row">
                        <div class="col-xs-4">
                            @if(!$post->chapter->previous_id===0)
                            <a href="#" class = "sosad-button btn btn-success btn-block disabled">这是第一章</a>
                            @else
                            <a href="{{ route('post.show', $post->chapter->previous_id) }}" class="btn btn-info btn-block btn-lg sosad-button">上一章</a>
                            @endif
                        </div>
                        <div class="col-xs-4">
                            <a href="{{ route('thread.chapter_index', $thread->id) }}" class="btn btn-info btn-block btn-lg sosad-button-control">目录</a>
                        </div>
                        <div class="col-xs-4">
                            @if(!$post->chapter->next_id===0)
                            <a href="#" class = "sosad-button btn btn-success btn-block disabled">这是最后一章</a>
                            @else
                            <a href="{{ route('post.show', $post->chapter->next_id) }}" class="btn btn-info btn-block btn-lg sosad-button">下一章</a>
                            @endif
                        </div>
                        <br>
                        <br>
                    </div>
                </div>
                @endif

            </div>
            @endif
        </div>
        <div class="text-center h3">
            <a href="{{ route('thread.show', ['thread' => $thread->id, 'withReplyTo' => $post->id]) }}" class="">>>前往讨论区查看全部评论</a>
        </div>
        <div class="contailer-fluid">
            <div class="row">
                <div class="col-xs-6">
                    <div class="text-center h4">
                        最新评论
                    </div>
                    @if ($post->new_reply)
                    <?php $reply = $post->new_reply; ?>
                    @include('posts._reply_body')
                    @endif
                </div>
                <div class="col-xs-6">
                    <div class="text-center h4">
                        高赞评论
                    </div>
                    @if ($post->top_reply)
                    <?php $reply = $post->top_reply; ?>
                    @include('posts._reply_body')
                    @endif
                </div>
            </div>
        </div>


        @if(Auth::check())
        @include('threads._reply')
        @endif
    </div>
</div>
@stop
