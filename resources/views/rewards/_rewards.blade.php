<!-- 简单展示一串打赏-->
@foreach($rewards as $reward)
@if($reward&&$reward->rewardable)
<article class="reward{{ $reward->id }}">
    <div class="h5">
        {{ $reward->created_at->diffForHumans() }}
        @if($reward->author)
        <a href="{{ route('user.show', $reward->user_id) }}">{{ $reward->author->name }}</a>
        @endif
        打赏了
        {{ $reward->reward_value }}
        {{ config('constants.rewards')[$reward->reward_type] }}
        给

        @switch($reward->rewardable_type)
        @case('post')
        <a href="{{ route('post.show', $reward->rewardable_id) }}">{{ $reward->rewardable->title.$reward->rewardable->brief }}</a>
        @break

        @case('thread')
        <a href="{{ route('thread.show', $reward->rewardable_id) }}">《{{ $reward->rewardable->title}}》{{$reward->rewardable->brief }}</a>
        @break

        @case('status')
        <a href="{{ route('status.show', $reward->rewardable_id) }}">{{$reward->rewardable->brief}}</a>
        @break

        @case('quote')
        <a href="{{ route('quote.show', $reward->rewardable_id) }}">{{ $reward->rewardable->body}}</a>
        @break

        @default
        {{ $reward->rewardable->title.$reward->rewardable->brief }}
        @endswitch

        @if(Auth::check()&&Auth::id()===$reward->user_id)
        &nbsp;&nbsp;<button onClick="delete_reward({{$reward->id}})" class="btn btn-xs sosad-button-control">删除打赏</button>
        @endif
    </div>
    <hr>
</article>
@endif
@endforeach
