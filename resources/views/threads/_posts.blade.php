<!-- 展示该主题下每一个帖子 -->
@foreach($posts as $key=>$post)
@if($post->fold_state==1)
<div class="text-center">
    <a type="button" data-toggle="collapse" data-target="#post{{ $post->id }}" style="cursor: pointer;" class="h6">该回帖被管理员折叠，点击展开</a>
</div>
@elseif($post->fold_state==2)
    <a type="button" data-toggle="collapse" data-target="#post{{ $post->id }}" style="cursor: pointer;" class="h6">该回帖被作者折叠，点击展开</a>
@endif
<div class="panel panel-default {{ $post->fold_state>0? 'collapse':'' }} " id = "post{{ $post->id }}">
    <div class="panel-heading">
        <div class="row">
            <div class="col-xs-12">
                <span>
                    <!-- 显示作者名称 -->
                    @if($post->author)
                        @if ($post->type==="chapter")
                            <span class="font-6 bianyuan-tag badge-tag">作者</span>
                        @elseif ($post->type==="review")
                            <span class="font-6  bianyuan-tag badge-tag">单主</span>
                        @elseif ($post->type==="answer")
                            <span class="font-6  bianyuan-tag badge-tag">答主</span>
                        @else
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

                    @if(($post->user_id>0)&&(!$post->is_anonymous)&&((!$thread->is_anonymous)||(($post->type==='post')||($post->type==='comment'))))
                        <span class="grayout smaller-25"><a href="{{ route('thread.show', ['thread'=>$thread->id, 'userOnly'=>$post->user_id]) }}">只看该用户</a></span>
                    @endif
                    <!-- 发表时间 -->
                    <span class="smaller-25">
                        {{ $post->created_at->diffForHumans() }}
                        @if($post->created_at < $post->edited_at )
                        /{{ $post->edited_at->diffForHumans() }}
                        @endif
                    </span>&nbsp;

                    @if((Auth::check())&&(Auth::user()->isAdmin()))
                    <span>
                        <a href="{{route('admin.postform', $post->id)}}" class="btn btn-xs btn-danger sosad-button admin-button"><span class="glyphicon glyphicon-user"></span>管理本帖</a>
                    </span>
                    @endif

                </span>
                <span class="pull-right smaller-20">
                    <a href="{{ route('post.show', $post->id) }}">No.{{ $post->id }}</a>
                </span>
            </div>
        </div>
    </div>
    <div class="panel-body post-body">
        @if( (($thread->is_bianyuan)||($post->is_bianyuan))&&(!Auth::check()) )
        <div class="text-center">
            <h6 class="display-4 grayout"><a href="route('login')">本内容只对注册用户开放，请登陆后查看</a></h6>
        </div>
        @elseif( (!$thread->recommended)&&($thread->channel()->type==='book')&&($thread->is_bianyuan)&&($post->type==='chapter')&&(Auth::check())&&(Auth::user()->level < 3)&&(Auth::id()!=$post->user_id) )
        <div class="text-center">
            <h6 class="display-4 grayout">本内容为非编推的边限文的正文章节，只对3级以上注册用户开放，请升级后查看</a></h6>
        </div>
        @elseif( (!$thread->recommended)&&($thread->channel()->type==='book')&&(!$thread->is_bianyuan)&&($post->is_bianyuan)&&($post->type==='chapter')&&(Auth::check())&&(Auth::user()->level < 2)&&(Auth::id()!=$post->user_id) )
        <div class="text-center">
            <h6 class="display-4 grayout">本内容为非编推的非边限文的单章限制章节，只对2级以上注册用户开放，请升级后查看</a></h6>
        </div>
        @elseif( (!$thread->recommended)&&($thread->channel()->type!='book')&&($thread->is_bianyuan||$post->is_bianyuan)&&(Auth::check())&&(Auth::user()->level < 1)&&(Auth::id()!=$post->user_id) )
        <div class="text-center">
            <h6 class="display-4 grayout">本内容为限制讨论，只对1级以上注册用户开放，请升级后查看</a></h6>
        </div>
        @else
            <!-- 回复他人帖子的相关信息 -->
            @if($post->reply_to_id!=0)
                <div class="post-reply grayout">
                    {{$post->type=='comment'?'点评':'回复'}}&nbsp;<a href="{{ route('thread.showpost', $post->reply_to_id) }}">{{ StringProcess::simpletrim($post->reply_to_brief, 30) }}</a>
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
            <div class="main-text {{ $post->use_indentation? 'indentation':'' }}">
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

                @if($post->use_markdown)
                {!! StringProcess::sosadMarkdown($post->body) !!}
                @else
                {!! StringProcess::wrapParagraphs($post->body) !!}
                @endif

                @if($post->type==="chapter"&&$post->chapter&&$post->chapter->annotation)
                <br>
                <div class="text-left grayout">
                    {!! StringProcess::wrapParagraphs($post->chapter->annotation) !!}
                </div>
                <br>
                @endif
                @if($post->type==='chapter')
                <div class="font-5">
                    <a href="{{ route('post.show', $post->id) }}" class="pull-left"><em>进入阅读模式</em></a>
                    <span class = "pull-right smaller-25"><em><span class="glyphicon glyphicon-pencil"></span>{{ $post->char_count }}/<span class="glyphicon glyphicon-eye-open"></span>{{ $post->view_count }}/<span class="glyphicon glyphicon glyphicon-comment"></span>{{ $post->reply_count }}</em></span>
                </div>
                @endif
            </div>
        @endif
    </div>

    @if(Auth::check())
    <div class="text-right post-vote h5">
        <span class="voteposts"><button class="btn btn-default btn-md" data-id="{{$post->id}}" onclick="vote('post', {{$post->id}}, 'upvote')" ><span class="glyphicon glyphicon-heart"></span><span id="post{{$post->id}}upvote">{{ $post->upvote_count }}</span></button></span>
        @if((!$thread->is_locked)&&(!$thread->noreply)&&(!Auth::user()->no_posting)&&($post->fold_state==0)&&(Auth::user()->level >= 2))
            <span ><a href = "#replyToThread" class="btn btn-default btn-md" onclick="replytopost({{ $post->id }}, '{{ StringProcess::trimtext($post->title.$post->brief, 40)}}');show_is_comment();"><span class="glyphicon glyphicon-comment">{{ $post->reply_count }}</span></a></span>
        @endif

        @if(($post->user_id===Auth::id())&&(!$thread->is_locked)&&($post->fold_state==0)&&($thread->channel()->allow_edit))
            <span><a class="btn btn-danger sosad-button btn-md" href="{{ route('post.edit', $post->id) }}">编辑</a></span>
        @endif

    </div>
    @endif

    @if ($post->last_reply)
    <div class="panel-footer">
        <div class="smaller-20" id="postcomment{{$post->last_reply_id}}">
            <a href="{{ route('thread.showpost', $post->last_reply_id) }}" class="grayout">最新回复：{{ StringProcess::simpletrim($post->last_reply->brief,20) }}</a>&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="{{ route('thread.show', ['thread' => $post->thread_id, 'withReplyTo' => $post->id]) }}" class="">>>本层全部回帖</a>
        </div>
    </div>
    @endif
</div>
@endforeach
