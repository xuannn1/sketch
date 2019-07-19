<!-- 简单展示一串帖子 -->
@foreach($activities as $key=>$activity)
@if($activity->item)
<article id="post{{ $activity->item_id }}">
    <span class="badge newchapter-badge badge-tag {{$activity->seen?'hidden':''}}">新</span>
    <a href="{{ route('thread.showpost', $activity->item_id) }}">
        @if($activity->item->is_anonymous)
        {{ $activity->item->majia ?? '匿名咸鱼' }}
        @else
        {{ $activity->item->author->name }}
        @endif
    {{ $activity->item->created_at->diffForHumans() }}
    @if($activity->item->simpleThread)
        在《{{$activity->item->simpleThread->title}}》中
    @endif
    @if($activity->type==8)
    圈了你（待做）
    @else
    回复了你
    @endif
    {{ $activity->item->brief }}
    </a>
    <hr>
</article>
@endif
@endforeach
