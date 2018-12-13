<div class="">
    <a type="btn btn-danger sosad-button" href="{{ route('home') }}"><span class="glyphicon glyphicon-home"></span><span>首页</span></a>
    &nbsp;/&nbsp;
    <a href="{{ route('channel.show', $thread->channel_id) }}">{{ $channel->channelname }}</a>
    &nbsp;/&nbsp;
    <a href="{{ route('channel.show', ['channel'=>$thread->channel_id,'label'=>$thread->label_id]) }}">{{ $label->labelname }}</a>
    &nbsp;/&nbsp;
    <a href="{{ route('thread.show',$thread->id) }}">{{ $thread->title }}</a>
</div>
