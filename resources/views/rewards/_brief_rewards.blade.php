<!-- 简单展示一串打赏-->
@foreach($rewards as $reward)
<article class="reward{{ $reward->id }}">
    <div class="">
        {{ $reward->created_at->diffForHumans() }}
        @if($reward->author)
        <a href="{{ route('user.show', $reward->user_id) }}">{{ $reward->author->name }}</a>
        @endif
        打赏了
        {{ $reward->reward_value }}
        {{ config('constants.rewards')[$reward->reward_type] }}
        @if(Auth::check()&&Auth::id()===$reward->user_id)
        &nbsp;&nbsp;<button onClick="delete_reward({{$reward->id}})" class="btn btn-xs sosad-button-control">删除打赏</button>
        @endif
    </div>
</article>
@endforeach
