<!-- 简单展示一串帖子 -->
@foreach($posts as $key=>$post)
<article id="post{{ $post->id }}">
    <a href="{{ route('thread.showpost', $post->id) }}">
        @if($post->anonymous)
        {{ $post->majia ?? '匿名咸鱼' }}
        @else
        {{ $post->name }}
        @endif
    {{ Carbon::parse($post->created_at)->diffForHumans() }}
    回复《{{ $post->title }}》
    {{ $post->brief }}
    </a>
</article>
<hr>
@endforeach
