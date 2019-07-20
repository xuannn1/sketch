<!-- 标题 -->
<div class="article-title {{ $thread->channel()->type == 'thread' ? '':'text-center' }}">
    <div class="font-2">
        <div>
            @if($thread->channel()->type==='book')
                <a href="{{ route('thread.show_profile',$thread->id) }}" class="font-1">{{ $thread->title }}</a>
            @else
                <a href="{{ route('thread.show',$thread->id) }}" class="font-1">{{ $thread->title }}</a>
            @endif
            <small>
                @if( !$thread->is_public )
                <span class="glyphicon glyphicon-eye-close"></span>
                @endif
                @if( $thread->is_locked )
                <span class="glyphicon glyphicon-lock"></span>
                @endif
                @if( $thread->no_reply )
                <span class="glyphicon glyphicon-warning-sign"></span>
                @endif
            </small>
            @if( $thread->is_bianyuan == 1)
            <span class="badge bianyuan-tag badge-tag">限</span>
            @endif
        </div>
    </div>
    <div class="font-3">
        @if( $thread->recommended)
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
    </div>
    <div class="">
        @if((Auth::check())&&(Auth::user()->isAdmin()))
        <a href="{{ route('admin.threadform', $thread) }}" class="btn btn-lg btn-danger admin-button">管理主题</a>
        @endif
    </div>
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
                @if ($thread->is_anonymous)
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
            @if($thread->channel()->type==='book')
            @if($thread->channel_id==2)
                <div class="">
                    @if($tag=$thread->tags->where('tag_type','同人原著')->first())
                    <a href="{{route('books.index', ['withTag'=>$tag->id])}}">{{$tag->tag_name}}({{$tag->tag_explanation}})</a>
                    @elseif($thread->tongren)
                    {{ $thread->tongren->tongren_yuanzhu }}
                    @endif
                    -
                    @if($tag=$thread->tags->where('tag_type','同人CP')->first())
                    <a href="{{route('books.index', ['withTag'=>$tag->id])}}">{{$tag->tag_name}}({{$tag->tag_explanation}})</a>
                    @elseif($thread->tongren)
                    {{ $thread->tongren->tongren_CP }}
                    @endif
                </div>
                <br>
            @endif
            <div class="">
                <a href="{{route('books.index', ['inChannel' => $thread->channel_id])}}">{{$thread->channel()->channel_name}}</a>
                @foreach($thread->tags->whereNotIn('tag_type', ['同人原著', '同人CP']) as $key => $tag)
                @if($key%4==3)
                <br>
                @else
                -
                @endif
                <a href="{{route('books.index', ['withTag'=>$tag->id])}}">{{$tag->tag_name}}</a>
                @endforeach
            </div>
            @endif
        </div>
    </div>
    <br>
    <!-- 首楼正文 -->
    <div class="main-text {{ $thread->channel()->type==='thread'&&$thread->use_indentation ? 'indentation':'' }}">
        @if(($thread->is_bianyuan)&&(!Auth::check()))
        <div class="text-center">
            <h6 class="display-4 grayout"><a href="{{ route('login') }}">主楼隐藏，请登录后查看</a></h6>
        </div>
        @else
        @if($thread->use_markdown)
        {!! StringProcess::sosadMarkdown($thread->body) !!}
        @else
        {!! StringProcess::wrapParagraphs($thread->body) !!}
        @endif
        @endif
    </div>
    <br>

    <!-- 信息汇总：总字数，阅读数，回应数，下载数 （以及如果允许下载，出现下载按钮）-->
    <div class="text-right">
        @if($thread->last_component&&$thread->last_component->type==='chapter')
        <span class="pull-left">
            <span>
                <a href="{{ route('post.show', $thread->last_component_id) }}">最新章节《{{$thread->last_component->title}}》</a>
            </span>
        </span>
        @endif
        <span class="smaller-20">
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
