<div class="smaller-10" id="postcomment{{$postcomment->id}}">
    @if($postcomment->anonymous)
    <span>{{ $postcomment->majia ?? '匿名咸鱼'}}</span>
    @if((Auth::check()&&(Auth::user()->admin)))
    <span class="admin-anonymous"><a href="{{ route('user.show', $postcomment->user_id) }}">{{ $postcomment->owner->name }}</a></span>
    @endif
    @else
    <a href="{{ route('user.show', $postcomment->owner->id) }}">{{ $postcomment->owner->name }}</a>
    @endif
    <span class="grayout">{{ Carbon\Carbon::parse($postcomment->created_at)->diffForHumans() }}：</span>
    @if(($thread->bianyuan)&&(!Auth::check()))
    <div class="text-center">
        <h6 class="grayout"><a href="{{ route('login') }}">登陆查看点评</a></h6>
    </div>
    @else
    {{ $postcomment->body }}
    @endif
    
    @if((Auth::check())&&(Auth::user()->admin))
    @include('admin._delete_postcomment')
    @endif
    @if(Auth::check())
    @include('posts._post_comment_vote')
    @endif
</div>
