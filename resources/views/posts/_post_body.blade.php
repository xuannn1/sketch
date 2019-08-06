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
        @if($post->type!='answer'&&$post->reply_to_id!=0)
            <div class="post-reply grayout">
                {{$post->type=='comment'?'点评':'回复'}}&nbsp;<a href="{{ route('thread.showpost', $post->reply_to_id) }}">{{ StringProcess::simpletrim($post->reply_to_brief, 30) }}</a>
            </div>
        @endif
        @if($post->type==='answer')
            <div class="post-reply grayout">
                问题：<a href="{{ route('thread.showpost', $post->reply_to_id) }}">{{$post->reply_to_brief}}</a>
            </div>
        @endif

        <!-- 展示推荐书籍内情 -->
        @if($post->type==='review'&&$post->review)
            <div class="grayout h4">
                @if($post->review->editor_recommend)
                <span class="recommend-label smaller-20">
                    <span class="glyphicon glyphicon-grain recommend-icon"></span>
                    <span class="recommend-text">推</span>
                </span>
                @endif
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
        <div class="main-text {{ $post->use_indentation? 'indentation':'' }} {{ $post->type==='chapter'?'chapter':'' }}">
            @if($post->title&&$show_post_mode==='thread')
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
                <div class="{{$show_post_mode==='thread'?'font-5':'font-4'}}">
                    @if($show_post_mode==='thread')
                    <a href="{{ route('post.show', $post->id) }}" class="pull-left"><em>进入阅读模式</em></a>
                    @else
                    <a href="{{ route('thread.showpost', $post->id) }}" class="pull-left"><em>进入论坛模式</em></a>
                    @endif
                    <span class = "pull-right smaller-25"><em><span class="glyphicon glyphicon-pencil"></span>{{ $post->char_count }}/<span class="glyphicon glyphicon-eye-open"></span>{{ $post->view_count }}/<span class="glyphicon glyphicon glyphicon-comment"></span>{{ $post->reply_count }}</em></span>
                </div>
            @endif
        </div>
    @endif
</div>
