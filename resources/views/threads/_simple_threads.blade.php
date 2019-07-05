@foreach($simplethreads as $thread)
<article class="{{ 'item2id'.$thread->thread_id }}">
    <div class="row">
        <div class="col-xs-12 h5">
            <!-- thread title -->
            <span>
                @if( $thread->top == 1)
                <span class="badge newchapter-badge badge-tag">置顶</span>
                @endif
                @if( $thread->jinghua > Carbon\Carbon::now())
                <span class="badge newchapter-badge badge-tag">精华</span>
                @endif
                <span class="bigger-20"><strong><a href="{{ route('thread.show', $thread->id) }}">
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
</article>
@endforeach
