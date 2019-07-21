<!-- 简单展示一串帖子 -->
@foreach($activities as $key=>$activity)
@if($activity->item)
<article id="post{{ $activity->item_id }}" class="h5">
    <span class="badge newchapter-badge badge-tag {{$activity->seen?'hidden':''}}">新</span>
    @if($activity->item->type=='post')
    <a href="{{ route('thread.showpost', $activity->item_id) }}">
    @elseif($activity->item->type=='comment')
    <a href="{{ route('post.show', $activity->item_id) }}">
    @endif

    <!-- 作者信息 -->
    <span>
        @if($activity->item->is_anonymous)
        {{ $activity->item->majia ?? '匿名咸鱼' }}
        @else
        {{ $activity->item->author->name }}
        @endif
    </span>
    <!-- 时间信息 -->
    <span>
        {{ $activity->item->created_at->diffForHumans() }}
    </span>
    @if($activity->item->simpleThread)
    <!-- 主题名称 -->
    <span>
        在《{{$activity->item->simpleThread->title}}》中
    </span>
    @endif

    @if($activity->type==8)
    圈了你
    @else
        @if($activity->item->type=='post')
            回复了你
        @else
            点评了你
        @endif
    @endif
    <span id="abbreviated{{$activity->item_id}}">
        {{ $activity->item->brief }}
    </span>
    
    </a>
    <span id="full{{$activity->item_id}}" class="hidden main-text">
        <div class="main-text">
            {!! StringProcess::wrapParagraphs($activity->item->body) !!}
        </div>
    </span>
    <a type="button" name="button" id="expand{{$activity->item_id}}" onclick="expanditem('{{$activity->item_id}}')">展开</a>
    <hr>
</article>
@endif
@endforeach
