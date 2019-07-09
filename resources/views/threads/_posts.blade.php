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
                    @if ($post->maintext)
                        <span>作者</span>
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

                    @if((!$post->anonymous)&&((!$thread->anonymous)||(!$post->maintext)))
                        <span class="grayout smaller-20"><a href="{{ route('thread.filterpost', ['thread'=>$thread->id, 'useronly'=>$post->user_id]) }}">只看该用户</a></span>
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
                <span class="pull-right">
                    <a href="{{ route('thread.showpost', $post) }}">No.{{ ($posts->currentPage()-1)*$posts->perPage()+$key+1 }}</a>
                </span>
            </div>
        </div>
    </div>
    <div class="panel-body post-body">
        @if((($post->bianyuan)||(($thread->bianyuan)))&&(!Auth::check()||(Auth::check()&&(Auth::user()->level < 1))))
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

            <!-- 普通回帖展开 -->
            <div class="main-text {{ $post->indentation? 'indentation':'' }}">
                @if($post->title)
                <div class="text-center">
                    <strong>{{ $post->title }}</strong>
                </div>
                @endif
                @if($post->markdown)
                {!! Helper::sosadMarkdown($post->body) !!}
                @else
                {!! Helper::wrapParagraphs($post->body) !!}
                @endif
            </div>
        @endif
    </div>

    @if(Auth::check())
    <div class="text-right post-vote">
        if($post->type<>'post'&&$post->type<>'comment')
        <a href="#" class="pull-left h6">帖子详情</a>
        @if(Auth::user()->level >= 1)
            <span class="voteposts"><button class="btn btn-default btn-xs" data-id="{{$post->id}}"  id = "{{$post->id.'upvote'}}" onclick="vote_post({{$post->id}},'upvote')" ><span class="glyphicon glyphicon-heart">{{ $post->upvote_count }}</span></button></span>
        @endif
        @if((!$thread->locked)&&(!$thread->noreply)&&(!Auth::user()->no_posting)&&(!$post->is_folded)&&(Auth::user()->level >= 2))
            <span ><a href = "#replyToThread" class="btn btn-default btn-xs" onclick="replytopost({{ $post->id }}, '{{ $post->brief }}')"><span class="glyphicon glyphicon-comment">{{ $post->reply_count }}</span></a></span>
        @endif

        @if(($post->user_id===Auth::id())&&(!$thread->locked)&&(!$post->is_folded)&&($thread->channel()->allow_edit))
            <span><a class="btn btn-danger sosad-button btn-xs" href="{{ route('post.edit', $post->id) }}">编辑</a></span>
        @endif

    </div>
    @endif

    @if ($post->last_reply)
    <div class="panel-footer">
        <div class="smaller-10" id="postcomment{{$post->last_reply_id}}">
            <a href="{{ route('thread.showpost', $post->last_reply_id) }}" class="grayout">最新回复：{{ $post->last_reply->brief }}</a>
        </div>
    </div>
    @endif
</div>
@endforeach
