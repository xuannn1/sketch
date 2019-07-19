<div class="panel panel-default">
    <div class="panel-body {{ $thread->channel()->type == 'thread' ? '':'text-center' }}">
        <!-- 标题简介数据打赏信息 -->
        @include('threads._thread_info')
    </div>
    <div class="panel-vote">
        <!-- 对主题进行投票／收藏／赞赏等操作 -->
        @include('threads._thread_action')
    </div>
    @foreach($thread->editor_recommends as $review)
        @include('threads._thread_review')
    @endforeach
</div>
