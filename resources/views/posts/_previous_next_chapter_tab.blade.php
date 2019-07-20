<div class="panel-body text-center">
    <div class="container-fluid">
        <br>
        <div class="row">
            <div class="col-xs-4">
                @if($post->chapter->previous_id<1)
                <a href="#" class = "sosad-button btn btn-md btn-success btn-block disabled">第一章</a>
                @else
                <a href="{{ route('post.show', $post->chapter->previous_id) }}" class="btn btn-info btn-block btn-md sosad-button">上一章</a>
                @endif
            </div>
            <div class="col-xs-4">
                <a href="{{ route('thread.chapter_index', $thread->id) }}" class="btn btn-info btn-block btn-md sosad-button-control">目录</a>
            </div>
            <div class="col-xs-4">
                @if($post->chapter->next_id<1)
                <a href="#" class = "sosad-button btn btn-md btn-success btn-block disabled">最后一章</a>
                @else
                <a href="{{ route('post.show', $post->chapter->next_id) }}" class="btn btn-info btn-block btn-md sosad-button">下一章</a>
                @endif
            </div>
            <br>
            <br>
        </div>
    </div>
</div>
