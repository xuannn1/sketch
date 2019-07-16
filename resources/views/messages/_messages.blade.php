<!-- 简单展示一串私信，右侧标注显示对话-->
@foreach($messages as $message)
<article id="message{{ $message->id }}">
    <div class="h5">
        <span class="badge {{$message->message_body->group_messaging?'':'hidden'}}">群发</span>
        @if($message->poster)
        <a href="{{route('user.show', $message->poster->id)}}">{{$message->poster->name}}</a>
        @endif
        To
        @if($message->receiver)
        <a href="{{route('user.show', $message->receiver->id)}}">{{$message->receiver->name}}</a>
        @endif
        <span class="grayout smaller-10">
            {{ $message->created_at->diffForHumans() }}
        </span>
        <span id="abbreviated{{$message->id}}">
            {!! StringProcess::trimtext($message->message_body->content,70) !!}
        </span>
        @if($show_dialogue_entry===true)
        <span class="pull-right">
            <a href="{{ route('message.dialogue', $message->poster_id==$user->id?$message->receiver_id:$message->poster_id) }}">
                &nbsp;&nbsp;>>展示对话
            </a>
        </span>
        @endif
        <span id="full{{$message->id}}" class="hidden main-text">
            {!! StringProcess::wrapParagraphs($message->message_body->content) !!}
        </span>
        <a type="button" name="button" id="expand{{$message->id}}" onclick="expanditem('{{$message->id}}')">展开</a>
    </div>
    <hr>
</article>

@endforeach
