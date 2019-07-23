@foreach($threads as $thread)
<article class="{{ 'threadid'.$thread->id }}">
    <div class="row">
        <div class="col-xs-12 h5">
            <!-- thread title -->
            <span>
                <span class="badge newchapter-badge badge-tag">
                    <a href="{{ route('channel.show', $thread->channel()->id) }}">
                    {{ $thread->channel()->channel_name }}
                    </a>
                </span>
                <a href="{{ route('thread.show',$thread->id) }}" class="bigger-5">{{ $thread->title }}</a>
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
                @if( $thread->recommended )
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
            <!-- thread title end   -->
            <!-- author  -->
            <span class = "pull-right smaller-5">
                @if($thread->author)
                    @if($thread->is_anonymous)
                        <span>{{ $thread->majia ?? '匿名咸鱼'}}</span>
                        @if((Auth::check()&&(Auth::user()->isAdmin())))
                            <span class="admin-anonymous"><a href="{{ route('user.show', $thread->user_id) }}">{{ $thread->author->name }}</a></span>
                        @endif
                    @else
                        <a href="{{ route('user.show', $thread->user_id) }}">{{ $thread->author->name }}</a>
                    @endif
                @endif
            </span>
            <!-- author end -->
        </div>
        <div class="col-xs-12 h5 ">
            <span class="smaller-5">{{ StringProcess::simpletrim($thread->brief, 15)  }}</span>
            <span class="pull-right smaller-30"><em><span class="glyphicon glyphicon-eye-open"></span>{{ $thread->view_count }}/<span class="glyphicon glyphicon glyphicon-comment"></span>{{ $thread->reply_count }}</em></span>
        </div>
        <div class="col-xs-12 h5 grayout brief-0">
            <span class="smaller-20"><a href="{{ route('thread.showpost', $thread->last_post_id) }}">{{ $thread->last_post? StringProcess::simpletrim($thread->last_post->brief, 15):' ' }}</a></span>
            <span class="pull-right smaller-20">{{ $thread->created_at->diffForHumans() }}/{{ $thread->responded_at->diffForHumans() }}</span>
        </div>
    </div>
</article>
<hr>
@endforeach
