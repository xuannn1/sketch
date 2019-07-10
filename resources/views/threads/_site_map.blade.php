<!-- 首页／版块／导航 -->
<div class="h3">
    <a type="btn btn-danger sosad-button" href="{{ route('home') }}"><span class="glyphicon glyphicon-home"></span><span>首页</span></a>
    &nbsp;/&nbsp;
    <a href="{{ route('channel.show', $thread->channel()->id) }}">{{ $thread->channel()->channel_name }}</a>
    &nbsp;/&nbsp;
    <a href="{{ route('thread.show_profile',$thread->id) }}">{{ $thread->title }}</a>
</div>
