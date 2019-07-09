<span>
    @if( $thread->tags->contains('tag_name', '置顶') )
    <span class="badge newchapter-badge badge-tag">置顶</span>
    @endif
    @if( $thread->tags->contains('tag_name', '精华') )
    <span class="badge newchapter-badge badge-tag">精华</span>
    @endif
    <a href="{{ route('thread.show',$thread->id) }}">{{ $thread->title }}</a>
    @if( !$thread->public )
    <span class="glyphicon glyphicon-eye-close"></span>
    @endif
    @if( $thread->locked )
    <span class="glyphicon glyphicon-lock"></span>
    @endif
    @if( $thread->no_reply )
    <span class="glyphicon glyphicon-warning-sign"></span>
    @endif
    @if( $thread->bianyuan == 1)
    <span class="badge bianyuan-tag badge-tag">限</span>
    @endif
    @if( $thread->tags->contains('tag_type', '编推') )
    <span class="recommend-label">
        <span class="glyphicon glyphicon-grain recommend-icon"></span>
        <span class="recommend-text">推</span>
    </span>
    @endif
    @if( $thread->tags->contains('tag_type', '管理') )
    <span class="jinghua-label">
        <span class="glyphicon glyphicon-thumbs-up jinghua-icon"></span>
    </span>
    @endif
</span>
