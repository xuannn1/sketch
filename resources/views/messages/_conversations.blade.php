@foreach($messages as $message)
<article class="">
    <hr>
    <div class="row">
        <div class="col-xs-12">
            <span class="badge">{{$message->group_messaging ? '群发消息' : ''}}</span>&nbsp;<span id="simple{{$message->id}}"><a href="{{ route('user.show', $message->poster_id) }}">{{ $message->poster_name }}</a>&nbsp;{{ Carbon\Carbon::parse($message->created_at)->diffForHumans() }}：
            </span>
        </div>
        @include('messages._message_body')
    </div>
</article>
@endforeach
