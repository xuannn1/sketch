<div class="panel panel-default">
    <div class="panel-body">
        <!-- 标题简介数据打赏信息 -->
        @include('threads._thread_info')
    </div>
    <div class="panel-vote">
        <!-- 对主题进行投票／收藏／赞赏等操作 -->
        @include('threads._thread_action')
    </div>
    @foreach($thread->editor_recommends as $review)
        @include('reviews._thread_review')
    @endforeach
</div>
@if($thread->channel()->type==='book')
<div class="panel panel-default">
    <div class="panel-body">
        @include('chapters._chapters')
    </div>
</div>
@endif
@if($thread->channel()->type==='list')
@include('reviews._reviews')
@endif
