<!-- 作者信息，发表时间 -->
<div>
    @if($thread->author)
        @if ($thread->anonymous)
            <span>{{ $thread->majia ?? '匿名咸鱼'}}</span>
            @if((Auth::check()&&(Auth::user()->isAdmin())))
                <span class="admin-anonymous"><a href="{{ route('user.show', $thread->user_id) }}">{{ $thread->author->name }}</a></span>
            @endif
        @else
            <a href="{{ route('user.show', $thread->user_id) }}">{{ $thread->author->name }}</a>
        @endif
    @endif
</div>
<p class="grayout smaller-20">
    发表于{{ $thread->created_at->diffForHumans() }}
    @if($thread->created_at < $thread->edited_at )
    修改于{{ $thread->edited_at->diffForHumans() }}
    @endif
</p>
