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
    <span id="full{{$post->id}}" class="hidden main-text {{ $post->type->chapter?'chapter':'' }}">
        <div class="main-text">
            {!! StringProcess::wrapParagraphs($post->body) !!}
        </div>
    </span>
    <a type="button" name="button" id="expand{{$post->id}}" onclick="expanditem('{{$post->id}}')">展开</a>
</article>
<hr>
@endforeach
