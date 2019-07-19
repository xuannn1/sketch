<!-- 简单展示一串评票-->
@foreach($votes as $vote)
<article class="vote{{ $vote->id }}">
    <div class="">
        {{ $vote->created_at->diffForHumans() }}
        @if($vote->author)
        <a href="{{ route('user.show', $vote->user_id) }}">{{ $vote->author->name }}</a>
        @endif
        发表了态度「{{config('constants.votes')[$vote->attitude_type]}}」
        @if(Auth::check()&&Auth::id()==$vote->user_id)
        &nbsp;&nbsp;<button onClick="delete_vote({{$vote->id}})" class="btn btn-xs sosad-button-control">删除评票</button>
        @endif
    </div>
</article>
@endforeach
