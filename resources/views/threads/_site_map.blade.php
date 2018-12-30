<div class="site-map">
     <a href="{{ route('home') }}">
         <!-- <span class="glyphicon glyphicon-home"></span> -->
         <span><i class="fa fa-home"></i>&nbsp;首页</span></a>
     <!-- &nbsp;/&nbsp; -->
     /
     <a href="{{ route('channel.show', $thread->channel_id) }}">{{ $thread->channel ? $thread->channel->channelname : '' }}</a>
     <!-- &nbsp;/&nbsp; -->
     /
     <a href="{{ route('channel.show', ['channel'=>$thread->channel_id,'label'=>$thread->label_id]) }}">{{ $thread->label->labelname }}</a>
     <!-- &nbsp;/&nbsp; -->
     /
     <a href="{{ route('thread.show',$thread->id) }}">{{ $thread->title }}</a>
</div>
