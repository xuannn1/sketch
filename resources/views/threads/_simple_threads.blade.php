@foreach($simplethreads as $thread)
<article class="{{ 'item2id'.$thread->thread_id }}">
    <div class="row thread">
        <div class="thread-info">
            <!-- thread title -->
            <span class="thread-title">
              @if( $thread->top == 1)
              <span class="btn-xs sosad-button-tag">置顶</span>
              @endif
                <a href="{{ route('thread.show', $thread->id) }}">{{ $thread->title }}</a>
                @if( $thread->bianyuan == 1)
                 <span class="badge">边</span>
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
