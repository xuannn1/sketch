<!-- 简单展示一串帖子 -->
@foreach($posts as $key=>$post)
<article id="post{{ $post->id }}">
    <a href="{{ route('thread.showpost', $post->id) }}">
        @if($post->is_anonymous)
        {{ $post->majia ?? '匿名咸鱼' }}
        @else
        {{ $post->name }}
        @endif
    {{ Carbon::parse($post->created_at)->diffForHumans() }}
    回复
    @if($post->title)
    《{{ $post->title }}》
    @endif
    <span id="abbreviated{{$post->id}}">
        {{ $post->brief }}
    </span>
    </a>
    <span id="full{{$post->id}}" class="hidden main-text">
        @if( $post->is_bianyuan&&!Auth::check() )
        <div class="text-center">
            <h6 class="display-4 grayout"><a href="route('login')">本内容只对注册用户开放，请登陆后查看</a></h6>
        </div>
        @elseif( $post->is_bianyuan&&Auth::check()&&Auth::user()->level < 1 )
        <div class="text-center">
            <h6 class="display-4 grayout">本内容为限制讨论，只对1级以上注册用户开放，请升级后查看</a></h6>
        </div>
        @else
        <div class="main-text">
            {!! StringProcess::wrapParagraphs($post->body) !!}
        </div>
        @endif
    </span>
    <a type="button" name="button" id="expand{{$post->id}}" onclick="expanditem('{{$post->id}}')">展开</a>
</article>
<hr>
@endforeach
