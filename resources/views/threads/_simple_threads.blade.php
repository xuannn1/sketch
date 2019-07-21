<!-- 置顶精华帖区域，每个帖展示很少的信息 -->
@foreach($simplethreads as $thread)
<article class="{{ 'thread'.$thread->id }} narrow-space">
    <div class="row">
        <div class="col-xs-12 h5">
            <!-- thread title -->
            <span>
                <span>
                    @if( $thread->tags->contains('tag_name', '置顶') )
                    <span class="badge newchapter-badge badge-tag">置顶</span>
                    @endif
                    @if( $thread->tags->contains('tag_name', '精华') )
                    <span class="badge newchapter-badge badge-tag">精华</span>
                    @endif
                    <a href="{{ route('thread.show', ['thread'=>$thread->id, 'withComponent'=>'no_comment']) }}" class="bigger-10">{{ $thread->title }}</a>
                    <small>
                        @if( !$thread->is_public )
                        <span class="glyphicon glyphicon-eye-close"></span>
                        @endif
                        @if( $thread->is_locked )
                        <span class="glyphicon glyphicon-lock"></span>
                        @endif
                        @if( $thread->no_reply )
                        <span class="glyphicon glyphicon-warning-sign"></span>
                        @endif
                    </small>
                    @if( $thread->is_bianyuan == 1)
                    <span class="badge bianyuan-tag badge-tag">限</span>
                    @endif
                    @if( $thread->recommended)
                    <span class="recommend-label">
                        <span class="glyphicon glyphicon-grain recommend-icon"></span>
                        <span class="recommend-text">推</span>
                    </span>
                    @endif
                    @if( $thread->tags->contains('tag_name', '精华') )
                    <span class="jinghua-label">
                        <span class="glyphicon glyphicon-thumbs-up jinghua-icon"></span>
                    </span>
                    @endif
                </span>
            </span>
            <span class="pull-right">
                @if($thread->author)
                    @if ($thread->is_anonymous)
                        <span>{{ $thread->majia ?? '匿名咸鱼'}}</span>
                    @else
                        <a href="{{ route('user.show', $thread->user_id) }}">{{ $thread->author->name }}</a>
                    @endif
                @endif
            </span>
            <!-- thread title end   -->
        </div>
    </div>
</article>
@endforeach
