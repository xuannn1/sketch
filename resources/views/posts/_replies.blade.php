@foreach($replies as $post)
<div class="panel panel-default" id = "post{{ $post->id }}">
    <div class="panel-heading">
        <div class="row">
            <div class="col-xs-12">
                <span>
                    <!-- 显示作者名称 -->
                    @if($post->author)
                        @if ($post->type==='chapter')
                            <span>作者</span>
                        @else
                            @if ($post->is_anonymous)
                            <span>{{ $post->majia ?? '匿名咸鱼'}}</span>
                                @if((Auth::check()&&(Auth::user()->isAdmin())))
                                <span class="admin-anonymous"><a href="{{ route('user.show', $post->user_id) }}">{{ $post->author->name }}</a></span>
                                @endif
                            @else
                                <a href="{{ route('user.show', $post->user_id) }}">{{ $post->author->name }}</a>
                            @endif
                        @endif
                    @endif


                    <!-- 发表时间 -->
                    <span class="smaller-20">
                        发表于 {{ $post->created_at->diffForHumans() }}
                        @if($post->created_at < $post->edited_at )
                        修改于 {{ $post->edited_at->diffForHumans() }}
                        @endif
                    </span>

                    @if((Auth::check())&&(Auth::user()->isAdmin()))
                    <span>
                        <a href="#" class="btn btn-xs btn-danger sosad-button admin-button"><span class="glyphicon glyphicon-user"></span>管理本帖</a>
                    </span>
                    @endif

                </span>
                <span class="pull-right">
                    <a href="{{ route('thread.showpost', $post) }}">No.{{ $post->id }}</a>
                </span>
            </div>
        </div>
    </div>
    <div class="panel-body post-body">
        @if((($post->is_bianyuan)||($thread->is_bianyuan))&&(!Auth::check()||(Auth::check()&&(Auth::user()->level < 1))))
        <div class="text-center">
            <h6 class="display-4 grayout"><a href="{{ route('login') }}">本内容为隐藏格式，只对1级以上注册用户开放，请登录或升级后查看</a></h6>
        </div>
        @else
            <!-- 添加这个帖子回复他人帖子的相关信息 -->
            @if($post->reply_to_id>0)
                <div class="post-reply grayout">
                    回复&nbsp;<a href="{{ route('thread.showpost', $post->reply_to_id) }}">{{ StringProcess::simpletrim($post->reply_to_brief, 20) }}</a>
                </div>
            @endif

            <!-- 回帖本体 -->
            <div class="main-text {{ $post->use_indentation? 'indentation':'' }}">
                @if($post->title)
                <div class="text-center">
                    <strong>{{ $post->title }}</strong>
                </div>
                @endif
                @if($post->use_markdown)
                {!! StringProcess::sosadMarkdown($post->body) !!}
                @else
                {!! StringProcess::wrapParagraphs($post->body) !!}
                @endif
            </div>
        @endif
    </div>

    @if(Auth::check())
    <div class="text-right post-vote">
        <!-- 点赞 -->
        @if(Auth::user()->level >= 1)
            <span class="voteposts"><button class="btn btn-default btn-xs" data-id="{{$post->id}}"  id = "{{$post->id.'upvote'}}" onclick="voteItem('post', {{$post->id}}, 'upvote')" ><span class="glyphicon glyphicon-heart">{{ $post->upvote_count }}</span></button></span>
        @endif

        <!-- 回复 -->
        @if((!$thread->is_locked)&&(!$thread->no_reply)&&(!Auth::user()->no_posting)&&($post->fold_state==0)&&(Auth::user()->level >= 2))
            <span ><a href = "#replyToThread" class="btn btn-default btn-xs" onclick="replytopost({{ $post->id }}, '{{ StringProcess::trimtext($post->title.$post->brief, 40) }}')"><span class="glyphicon glyphicon-comment">{{ $post->reply_count }}</span></a></span>
        @endif

        <!-- 编辑 -->
        @if(($post->user_id===Auth::id())&&(!$thread->is_locked)&&($post->fold_state==0)&&($thread->channel()->allow_edit))
            <span><a class="btn btn-danger sosad-button btn-xs" href="{{ route('post.edit', $post->id) }}">编辑</a></span>
        @endif

    </div>
    @endif
</div>
@endforeach
