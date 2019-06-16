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
    @if((($post->bianyuan)||(($thread->bianyuan)))&&(!Auth::check()||(Auth::check()&&(Auth::user()->user_level < 1))))
        <span class="grayout"><a href="{{ route('login') }}">本内容为隐藏格式，只对1级以上注册用户开放，请登录或升级后查看</a></span>
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
