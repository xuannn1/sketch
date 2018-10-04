<li role="presentation">
    <a href="{{ route('statuses.collections') }}" class="thread-title {{$active==1? 'active':''}}">关注动态</a>
</li>
<li role="presentation">
    <a href="{{ route('statuses.index') }}" class="thread-title {{$active==0? 'active':''}}">全站动态</a>
</li>
<li role="presentation">
    <a href="{{ route('users.index') }}" class="thread-title {{$active==2? 'active':''}}">全部用户</a>
</li>
