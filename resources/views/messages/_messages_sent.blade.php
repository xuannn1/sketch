@foreach($messages_sent as $message)
<article class="messages_sent margin5">
    <div class="row">
        <div class="margin5">
            <span class="badge">{{$message->group_messaging ? '群发消息' : ''}}</span>&nbsp;<span id="simple{{$message->id}}"><a href="{{ route('user.show', $message->receiver_id) }}">{{ $message->receiver_name }}</a>&nbsp;{{ Carbon\Carbon::parse($message->created_at)->diffForHumans() }}：
            </span>
            <span class="pull-right"><a href="{{ route('messages.conversation', ['user' => $message->receiver_id, 'is_group_messaging' => 3]) }}">
              <i class="fas fa-comments"></i>
              查看对话
            </a></span>
        </div>
        @include('messages._message_profile')
    </div>
</article>
@endforeach
