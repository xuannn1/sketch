<span>
    <a href="{{ route('thread.show',$thread->id) }}">{{ $thread->title }}</a><small>
        @if(!$thread->public)
        <span class="glyphicon glyphicon-eye-close"></span>
        @endif
        @if($thread->locked)
        <span class="glyphicon glyphicon-lock"></span>
        @endif
        @if($thread->noreply)
        <span class="glyphicon glyphicon-warning-sign"></span>
        @endif
        @if( $thread->bianyuan == 1)
        <span class="badge bianyuan-tag badge-tag">限</span>
        @endif
        @if( $thread->recommended == 1)
        <span class="recommend-label">
            <span class="glyphicon glyphicon-grain recommend-icon"></span>
            <span class="recommend-text">推</span>
        </span>
        @endif
        @if( $thread->jinghua > Carbon\Carbon::now())
        <span class="jinghua-label">
            <span class="glyphicon glyphicon-thumbs-up jinghua-icon"></span>
        </span>
        @endif

    </small>
</span>
