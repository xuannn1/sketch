<!-- 简单展示一串打赏-->
@foreach($votes as $vote)
@if($vote&&$vote->votable)
<article class="vote{{ $vote->id }}">
    <div class="h5">
        {{ $vote->created_at->diffForHumans() }}
        @if($vote->author)
        <a href="{{ route('user.show', $vote->user_id) }}">{{ $vote->author->name }}</a>
        @endif
        发表了态度「{{config('constants.votes')[$vote->attitude_type]}}」

        @switch($vote->votable_type)
        @case('post')
        <a href="{{ route('post.show', $vote->votable_id) }}">{{ $vote->votable->title.$vote->votable->brief }}</a>
        @break

        @case('thread')
        <a href="{{ route('thread.show', $vote->votable_id) }}">《{{ $vote->votable->title}}》{{$vote->votable->brief }}</a>
        @break

        @case('status')
        Second case...
        @break

        @case('quote')
        <a href="{{ route('quote.show', $vote->votable_id) }}">{{ $vote->votable->body}}</a>
        @break

        @default
        {{ $vote->votable->title.$vote->votable->brief }}
        @endswitch

        @if(Auth::check()&&Auth::id()==$vote->user_id)
        &nbsp;&nbsp;<button onClick="delete_vote({{$vote->id}})" class="btn btn-xs sosad-button-control">删除评票</button>
        @endif
    </div>
    <hr>
</article>
@endif
@endforeach
