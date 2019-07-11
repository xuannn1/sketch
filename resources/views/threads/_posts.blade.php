<!-- 展示该主题下每一个帖子 -->
@foreach($posts as $key=>$post)
@if($post->is_folded)
<div class="text-center">
    <a type="button" data-toggle="collapse" data-target="#post{{ $post->id }}" style="cursor: pointer;" class="h6">该回帖被折叠，点击展开</a>
</div>
@endif
<div class="panel panel-default {{ $post->is_folded ? 'collapse':'' }} " id = "post{{ $post->id }}">
    <div class="panel-heading">
        <div class="row">
            <div class="col-xs-12">
                <span>
                    <!-- 显示作者名称 -->
                    @if($post->author)
                        @if ($post->type==="chapter")
                            <span>作者</span>
                        @elseif ($post->type==="review"||$post->type==="answer")
                            <span>作者</span>
                        @else
                            @if ($post->anonymous)
                                <span>{{ $post->majia ?? '匿名咸鱼'}}</span>
                                @if((Auth::check()&&(Auth::user()->isAdmin())))
                                <span class="admin-anonymous"><a href="{{ route('user.show', $post->user_id) }}">{{ $post->author->name }}</a></span>
                                @endif
                            @else
                                <a href="{{ route('user.show', $post->user_id) }}">
                                    <span>lv.{{ $post->author->level }}</span>
                                    @if($post->author->title&&$post->author->title->name)
                                    <span>{{ $post->author->title->name }}</span>
                                    @endif
                                    {{ $post->author->name }}
                                </a>
                            @endif
                        @endif
                    @endif

                    @if(($post->user_id>0)&&(!$post->anonymous)&&((!$thread->anonymous)||(!$post->maintext)))
                        <span class="grayout smaller-20"><a href="{{ route('thread.show', ['thread'=>$thread->id, 'userOnly'=>$post->user_id]) }}">只看该用户</a></span>
                    @endif
                    <!-- 发表时间 -->
                    <span class="smaller-20">
                        发表于 {{ $post->created_at->diffForHumans() }}
                        @if($post->created_at < $post->edited_at )
                        修改于 {{ $post->edited_at->diffForHumans() }}
                        @endif
                    </span>&nbsp;

                    @if((Auth::check())&&(Auth::user()->isAdmin()))
                    <span>
                        <a href="#" class="btn btn-xs btn-danger sosad-button admin-button"><span class="glyphicon glyphicon-user"></span>管理本帖</a>
                    </span>
                    @endif

                </span>
                <span class="pull-right smaller-20">
                    <a href="{{ route('thread.showpost', $post) }}">No.{{ $post->id }}</a>
                </span>
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
                    回复&nbsp;<a href="{{ route('thread.showpost', $post->reply_to_id) }}">{{ StringProcess::simpletrim($post->reply_to_brief, 30) }}</a>
                </div>
            @endif

            <!-- 展示推荐书籍内情 -->
            @if($post->type==='review'&&$post->review)
                <div class="post-reply grayout h4">
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
                    <strong><a href="{{ route('post.show', $post->id) }}">{{ $post->title }}</a></strong>
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

                @if($post->type==="chapter"&&$post->chapter&&$post->chapter->annotation)
                <br>
                <div class="text-left grayout">
                    {!! Helper::sosadMarkdown($post->chapter->annotation) !!}
                </div>
                @endif
            </div>
        @endif
    </div>

    @if(Auth::check())
    <div class="text-right post-vote h5">
        @if($post->type==='chapter')
        <a href="#" class="pull-left h5"><em>前往阅读模式</em></a>
        @endif
        @if(Auth::user()->level >= 1)
            <span class="voteposts"><button class="btn btn-default btn-md" data-id="{{$post->id}}"  id = "{{$post->id.'upvote'}}" onclick="vote_post({{$post->id}},'upvote')" ><span class="glyphicon glyphicon-heart">{{ $post->upvote_count }}</span></button></span>
        @endif
        @if((!$thread->locked)&&(!$thread->noreply)&&(!Auth::user()->no_posting)&&(!$post->is_folded)&&(Auth::user()->level >= 2))
            <span ><a href = "#replyToThread" class="btn btn-default btn-md" onclick="replytopost({{ $post->id }}, '{{ StringProcess::trimtext($post->title.$post->brief, 40) }}')"><span class="glyphicon glyphicon-comment">{{ $post->reply_count }}</span></a></span>
        @endif

        @if(($post->user_id===Auth::id())&&(!$thread->locked)&&(!$post->is_folded)&&($thread->channel()->allow_edit))
            <span><a class="btn btn-danger sosad-button btn-md" href="{{ route('post.edit', $post->id) }}">编辑</a></span>
        @endif

    </div>
    @endif

    @if ($post->last_reply)
    <div class="panel-footer">
        <div class="smaller-20" id="postcomment{{$post->last_reply_id}}">
            <a href="{{ route('thread.showpost', $post->last_reply_id) }}" class="grayout">最新回复：{{ StringProcess::simpletrim($post->last_reply->brief,20) }}</a>
            <a href="{{ route('thread.show', ['thread' => $post->thread_id, 'withReplyTo' => $post->id]) }}" class="pull-right">>>全部回帖</a>
        </div>
    </div>
    @endif
</div>
@endforeach
