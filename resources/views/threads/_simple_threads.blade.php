@foreach($simplethreads as $thread)
<article class="{{ 'item2id'.$thread->thread_id }}">
    <div class="row">
        <div class="col-xs-12 h5">
            <!-- thread title -->
            <span>
                @if( $thread->top == 1)
                <span class="btn btn-xs btn-success sosad-button tag-button-left tag-red">置顶</span>
                @endif
                <span class="bigger-20"><strong><a href="{{ route('thread.show', $thread->thread_id) }}">
                    {{ $thread->title }}
                </a></strong></span>
                @if( $thread->bianyuan == 1)
                <span class="badge bianyuan-tag badge-tag">限</span>
                @endif
                <small>
                    @if(!$thread->public)
                    <span class="glyphicon glyphicon-eye-close"></span>
                    @endif
                    @if($thread->locked)
                    <span class="glyphicon glyphicon-lock"></span>
                    @endif
                    @if($thread->noreply)
                    <span class="glyphicon glyphicon-warning-sign"></span>
                    @endif
                </small>
            </span>
            <!-- thread title end   -->
        </div>
    </div>
    <hr>
</article>
@endforeach
