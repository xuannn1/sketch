<div class="panel panel-default" id = "post{{ $reply->id }}">
    <div class="panel-heading">
        <div class="row">
            <div class="col-xs-12">
                <span>
                    <!-- 显示作者名称 -->
                    @if($reply->author)
                        @if ($reply->maintext)
                            <span>作者</span>
                        @else
                            @if ($reply->anonymous)
                            <span>{{ $reply->majia ?? '匿名咸鱼'}}</span>
                                @if((Auth::check()&&(Auth::user()->isAdmin())))
                                <span class="admin-anonymous"><a href="{{ route('user.show', $reply->user_id) }}">{{ $reply->author->name }}</a></span>
                                @endif
                            @else
                                <a href="{{ route('user.show', $reply->user_id) }}">{{ $reply->author->name }}</a>
                            @endif
                        @endif
                    @endif


                    <!-- 发表时间 -->
                    <span class="smaller-20">
                        发表于 {{ $reply->created_at->diffForHumans() }}
                        @if($reply->created_at < $reply->edited_at )
                        修改于 {{ $reply->edited_at->diffForHumans() }}
                        @endif
                    </span>

                    @if((Auth::check())&&(Auth::user()->isAdmin()))
                    <span>
                        <a href="#" class="btn btn-xs btn-danger sosad-button admin-button"><span class="glyphicon glyphicon-user"></span>管理本帖</a>
                    </span>
                    @endif

                </span>
                <span class="pull-right">
                    <a href="{{ route('thread.showpost', $reply) }}">No.{{ $reply->id }}</a>
                </span>
            </div>
        </div>
    </div>
    <div class="panel-body post-body">
        @if((($reply->bianyuan)||($thread->bianyuan))&&(!Auth::check()||(Auth::check()&&(Auth::user()->level < 1))))
        <div class="text-center">
            <h6 class="display-4 grayout"><a href="{{ route('login') }}">本内容为隐藏格式，只对1级以上注册用户开放，请登录或升级后查看</a></h6>
        </div>
        @else
            <!-- 回复他人帖子的相关信息 -->
            @if($reply->reply_to_id!=0)
                <div class="post-reply grayout">
                    回复&nbsp;<a href="{{ route('thread.showpost', $reply->reply_to_id) }}">{{ $reply->reply_to_brief }}</a>
                </div>
            @endif

            <!-- 回帖本体 -->
            <div class="main-text {{ $reply->indentation? 'indentation':'' }}">
                @if($reply->title)
                <div class="text-center">
                    <strong>{{ $reply->title }}</strong>
                </div>
                @endif
                @if($reply->markdown)
                {!! Helper::sosadMarkdown($reply->body) !!}
                @else
                {!! Helper::wrapParagraphs($reply->body) !!}
                @endif
            </div>
        @endif
    </div>

    @if(Auth::check())
    <div class="text-right post-vote">
        <!-- 点赞 -->
        @if(Auth::user()->level >= 1)
            <span class="voteposts"><button class="btn btn-default btn-xs" data-id="{{$reply->id}}"  id = "{{$reply->id.'upvote'}}" onclick="vote_post({{$reply->id}},'upvote')" ><span class="glyphicon glyphicon-heart">{{ $reply->upvote_count }}</span></button></span>
        @endif

        <!-- 回复 -->
        @if((!$thread->locked)&&(!$thread->noreply)&&(!Auth::user()->no_posting)&&(!$reply->is_folded)&&(Auth::user()->level >= 2))
            <span ><a href = "#replyToThread" class="btn btn-default btn-xs" onclick="replytopost({{ $reply->id }}, '{{ StringProcess::trimtext($reply->title.$reply->brief, 40) }}')"><span class="glyphicon glyphicon-comment">{{ $reply->reply_count }}</span></a></span>
        @endif

        <!-- 编辑 -->
        @if(($reply->user_id===Auth::id())&&(!$thread->locked)&&(!$reply->is_folded)&&($thread->channel()->allow_edit))
            <span><a class="btn btn-danger sosad-button btn-xs" href="{{ route('post.edit', $reply->id) }}">编辑</a></span>
        @endif

    </div>
    @endif
</div>
