<!-- 首页／版块／类型 -->
<div class="site-map">
   <a type="btn btn-danger sosad-button" href="{{ route('home') }}"><span class="glyphicon glyphicon-home"></span><span>首页</span></a>
   &nbsp;/&nbsp;
   <a href="{{ route('channel.show', $thread->channel_id) }}">{{ $thread->channel->channelname }}</a>
   &nbsp;/&nbsp;
   <a href="{{ route('label.show', $thread->label) }}">{{ $thread->label->labelname }}</a>
   &nbsp;/&nbsp;
   <a href="{{ route('thread.show',$thread->id) }}">{{ $thread->title }}</a>
</div>
