<!-- 简单展示一串帖子 -->
@foreach($activities as $key=>$activity)
@if($activity->item)
<?php $post=$activity->item; ?>
<article id="post{{ $post->id }}" class="h5">
    <span class="badge newchapter-badge badge-tag {{$activity->seen?'hidden':''}}">新</span>
    @if($post->type=='post')
    <a href="{{ route('thread.showpost', $post->id) }}">
    @elseif($post->type=='comment')
    <a href="{{ route('post.show', $post->id) }}">
    @endif

    <!-- 作者信息 -->
    <span>
        @if($post->is_anonymous)
        {{ $post->majia ?? '匿名咸鱼' }}
        @else
        {{ $post->author->name }}
        @endif
    </span>
    <!-- 时间信息 -->
    <span>
        {{ $post->created_at->diffForHumans() }}
    </span>
    @if($post->simpleThread)
    <!-- 主题名称 -->
    <span>
        在《{{$post->simpleThread->title}}》中
    </span>
    @endif

    @if($activity->type==8)
    圈了你
    @else
        @if($post->type=='post')
            回复道
        @else
            点评道
        @endif
    @endif
    <span id="abbreviated{{$post->id}}">
        {{ $post->brief }}
    </span>

    </a>
    <span id="full{{$post->id}}" class="hidden main-text">
        <div class="main-text">
            {!! StringProcess::wrapParagraphs($post->body) !!}
        </div>
    </span>
    &nbsp;&nbsp;&nbsp;<a type="button" name="button" id="expand{{$post->id}}" onclick="expanditem('{{$post->id}}')">展开</a>
    <hr>
</article>
@endif
@endforeach
