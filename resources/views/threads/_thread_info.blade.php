<!-- 标题 -->
<div class="article-title {{ $thread->channel()->type == 'thread' ? '':'text-center' }}">
    <h2>
        <span>
            <a href="{{ route('thread.show_profile',$thread->id) }}" class="h1">{{ $thread->title }}</a>
            <small>
                @if( !$thread->public )
                <span class="glyphicon glyphicon-eye-close"></span>
                @endif
                @if( $thread->locked )
                <span class="glyphicon glyphicon-lock"></span>
                @endif
                @if( $thread->no_reply )
                <span class="glyphicon glyphicon-warning-sign"></span>
                @endif
            </small>
            @if( $thread->bianyuan == 1)
            <span class="badge bianyuan-tag badge-tag">限</span>
            @endif
            @if( $thread->tags->contains('tag_type', '编推') )
            <span class="recommend-label">
                <span class="glyphicon glyphicon-grain recommend-icon"></span>
                <span class="recommend-text">推</span>
            </span>
            @endif
            @if( $thread->tags->contains('tag_type', '管理') )
            <span class="jinghua-label">
                <span class="glyphicon glyphicon-thumbs-up jinghua-icon"></span>
            </span>
            @endif
        </span>
        @if((Auth::check())&&(Auth::user()->isAdmin()))
        @include('admin._modify_thread')
        @endif
    </h2>
</div>
<!-- 简介 -->
<div class="article-body h4">
    <!-- 简介信息 -->
    <div class="">
        <!-- 一句话简介 -->
        <div class="h4">{{ $thread->brief }}</div>
        <div class="text-center">
            <!-- 作者信息，发表时间 -->
            <div class="">
                @if($thread->author)
                @if ($thread->anonymous)
                <span>{{ $thread->majia ?? '匿名咸鱼'}}</span>
                @if((Auth::check()&&(Auth::user()->isAdmin())))
                <span class="admin-anonymous"><a href="{{ route('user.show', $thread->user_id) }}">{{ $thread->author->name }}</a></span>
                @endif
                @else
                <a href="{{ route('user.show', $thread->user_id) }}">{{ $thread->author->name }}</a>
                @endif
                @endif
            </div>
            <p class="grayout smaller-20">
                发表于{{ $thread->created_at->diffForHumans() }}
                @if($thread->created_at < $thread->edited_at )
                修改于{{ $thread->edited_at->diffForHumans() }}
                @endif
            </p>
        </div>
    </div>

    <!-- 首楼正文 -->
    <div class="main-text {{ $thread->channel()->type==='thread'&&$thread->indentation ? 'indentation':'' }}">
        @if(($thread->bianyuan)&&(!Auth::check()))
        <div class="text-center">
            <h6 class="display-4 grayout"><a href="{{ route('login') }}">主楼隐藏，请登录后查看</a></h6>
        </div>
        @else
        @if($thread->markdown)
        {!! Helper::sosadMarkdown($thread->body) !!}
        @else
        {!! Helper::wrapParagraphs($thread->body) !!}
        @endif
        @endif
    </div>
    <br>

    <!-- 信息汇总：总字数，阅读数，回应数，下载数 （以及如果允许下载，出现下载按钮）-->
    <div class="text-right">
        <span class = "smaller-10">
            <em>
                @if($thread->total_char>0)
                <span class="glyphicon glyphicon-pencil"></span>{{ $thread->total_char }}/
                @endif
                <span class="glyphicon glyphicon-eye-open"></span>{{ $thread->view_count }}/<span class="glyphicon glyphicon-comment"></span>{{ $thread->reply_count }}/<span class="glyphicon glyphicon-save"></span>{{ $thread->download_count }}
            </em>
            @if($thread->download_as_thread||$thread->download_as_book)
            &nbsp;&nbsp;<span><a href="{{ route('download.index', $thread) }}">下载</a></span>
            @endif
        </span>
    </div>
</div>
