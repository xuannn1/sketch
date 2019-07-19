@extends('layouts.default')
@section('title', $thread->title )

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <!-- 首页／版块／导航 -->
        <div class="">
            <a type="btn btn-danger sosad-button" href="{{ route('home') }}"><span class="glyphicon glyphicon-home"></span><span>首页</span></a>
            &nbsp;/&nbsp;
            <a href="{{ route('channel.show', $thread->channel()->id) }}">{{ $thread->channel()->channel_name }}</a>
            &nbsp;/&nbsp;
            <a href="{{ route('thread.show_profile',$thread->id) }}">{{ $thread->title }}</a>
            &nbsp;/&nbsp;
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
                                            <span>{{ $post->author->title->name }}</span>
                                            @endif
                                            {{ $post->author->name }}
                                        </a>
                                    @endif
                                @endif
                            @endif
                        </div>

                        <!-- 发表时间 -->
                        <div class="">
                            <span class="smaller-20">
                                发表于 {{ $post->created_at->diffForHumans() }}
                                @if($post->created_at < $post->edited_at )
                                修改于 {{ $post->edited_at->diffForHumans() }}
                                @endif
                            </span>&nbsp;
                        </div>
                        @if((Auth::check())&&(Auth::user()->isAdmin()))
                        <!-- 管理专区 -->
                        <div class="">
                            <span>
                                <a href="#" class="btn btn-md btn-danger sosad-button admin-button"><span class="glyphicon glyphicon-user"></span>管理本帖</a>
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @if($post->type==="chapter"&&$post->chapter)
                @include('posts._previous_next_chapter_tab')
                <br>
            @endif
            <div class="panel-body post-body">
                @if( (($thread->is_bianyuan)||($post->is_bianyuan))&&(!Auth::check()) )
                <div class="text-center">
                    <h6 class="display-4 grayout"><a href="route('login')">本内容只对注册用户开放，请登陆后查看</a></h6>
                </div>
                @elseif( (!$thread->recommended)&&($thread->channel()->type==='book')&&($thread->is_bianyuan)&&($post->type==='chapter')&&(Auth::check())&&(Auth::user()->level < 3) )
                <div class="text-center">
                    <h6 class="display-4 grayout">本内容为非编推的边限文的正文章节，只对3级以上注册用户开放，请升级后查看</a></h6>
                </div>
                @elseif( (!$thread->recommended)&&($thread->channel()->type==='book')&&(!$thread->is_bianyuan)&&($post->is_bianyuan)&&($post->type==='chapter')&&(Auth::check())&&(Auth::user()->level < 2) )
                <div class="text-center">
                    <h6 class="display-4 grayout">本内容为非编推的非边限文的单章限制章节，只对2级以上注册用户开放，请升级后查看</a></h6>
                </div>
                @elseif( (!$thread->recommended)&&($thread->channel()->type!='book')&&($thread->is_bianyuan||$post->is_bianyuan)&&(Auth::check())&&(Auth::user()->level < 1) )
                <div class="text-center">
                    <h6 class="display-4 grayout">本内容为限制讨论，只对1级以上注册用户开放，请升级后查看</a></h6>
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
                    <div class="main-text {{ $post->use_indentation? 'indentation':'' }}">

                        @if($post->type==="chapter"&&$post->chapter&&$post->chapter->warning)
                        <div class="text-center grayout">
                            {{ $post->chapter->warning }}
                        </div>
                        <br>
                        @endif

                        @if($post->use_markdown)
                        {!! StringProcess::sosadMarkdown($post->body) !!}
                        @else
                        {!! StringProcess::wrapParagraphs($post->body) !!}
                        @endif
                        <br>

                    </div>
                    @if($post->type==="chapter"&&$post->chapter&&$post->chapter->annotation)
                    <br>
                    <div class="text-left grayout">
                        {!! StringProcess::wrapParagraphs($post->chapter->annotation) !!}
                    </div>
                    <br>
                    @endif

                    <div class="font-4">
                        <a href="{{ route('thread.showpost', $post->id) }}" class="pull-left"><em>进入论坛模式</em></a>
                        <span class = "pull-right smaller-20"><em>
                            <span class="glyphicon glyphicon-pencil"></span>{{ $post->char_count }}/
                            <span class="glyphicon glyphicon-eye-open"></span>{{ $post->view_count }}/<span class="glyphicon glyphicon glyphicon-comment"></span>{{ $post->reply_count }}
                        </em></span>
                    </div>

                @endif
            </div>
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
            <div class="text-right post-vote">
                @if(Auth::user()->level >= 1)
                    <span class="voteposts"><button class="btn btn-default btn-lg" data-id="{{$post->id}}"  id = "{{$post->id.'upvote'}}" onclick="vote_post({{$post->id}},'upvote')" ><span class="glyphicon glyphicon-heart">{{ $post->upvote_count }}</span></button></span>
                @endif
                @if((!$thread->is_locked)&&(!$thread->noreply)&&(!Auth::user()->no_posting)&&($post->fold_state==0)&&(Auth::user()->level >= 2))
                    &nbsp;<span ><a href = "#replyToThread" class="btn btn-default btn-lg" onclick="replytopost({{ $post->id }}, '{{ StringProcess::trimtext($post->title.$post->brief, 40) }}')"><span class="glyphicon glyphicon-comment">{{ $post->reply_count }}</span></a></span>
                @endif
                &nbsp;<span><a href="#" data-id="{{$post->id}}" data-toggle="modal" data-target="#TriggerPostReward{{ $post->id }}" class="btn btn-default btn-lg">打赏</a></span>
                @if(($post->user_id===Auth::id())&&(!$thread->is_locked)&&($post->fold_state==0)&&($thread->channel()->allow_edit))
                    &nbsp;<span><a class="btn btn-lg btn-danger sosad-button btn-md" href="{{ route('post.edit', $post->id) }}">编辑</a></span>
                @endif
                @if($thread->user_id===Auth::id())
                &nbsp;
                <a href="#" data-id="{{$post->id}}" data-toggle="modal" data-target="#TriggerAdvancedManaging{{ $post->id }}" class="btn btn-default btn-lg"><i class="fa fa-cog " aria-hidden="true"></i></a>
                @include('posts._management_form')
                @endif
            </div>
            @include('posts._reward_form')
            @endif

        </div>
        <div class="text-center h3">
            <a href="{{ route('thread.show', ['thread' => $thread->id, 'withReplyTo' => $post->id]) }}" class="">>>前往讨论区查看本帖评论</a>
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
