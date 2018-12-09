<!-- 作者信息，发表时间 -->
<div>
    @if ($thread->anonymous)
    <span>{{ $thread->majia ?? '匿名咸鱼'}}</span>
    @if((Auth::check()&&(Auth::user()->admin)))
    <span class="admin-anonymous"><a href="{{ route('user.show', $thread->user_id) }}">{{ $thread->creator->name }}</a></span>
    @endif
    @else
    <a href="{{ route('user.show', $thread->user_id) }}">{{ $thread->creator->name }}</a>
    @endif
</div>
<p class="grayout smaller-20">
    发表于{{ Carbon\Carbon::parse($thread->created_at)->diffForHumans() }}
    @if($thread->created_at < $thread->edited_at )
    修改于{{ Carbon\Carbon::parse($thread->edited_at)->diffForHumans() }}
    @endif
</p>
