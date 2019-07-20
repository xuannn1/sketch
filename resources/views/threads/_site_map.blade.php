<!-- 首页／版块／导航 -->
<div class="">
    <a type="btn btn-danger sosad-button" href="{{ route('home') }}"><span class="glyphicon glyphicon-home"></span><span>首页</span></a>
    /
    <a href="{{ route('channel.show', $thread->channel()->id) }}">{{ $thread->channel()->channel_name }}</a>
    /
    @if($thread->channel()->type==='book')
        <a href="{{ route('thread.show_profile',$thread->id) }}">{{ $thread->title }}</a>
    @else
        <a href="{{ route('thread.show',$thread->id) }}">{{ $thread->title }}</a>
    @endif
</div>
