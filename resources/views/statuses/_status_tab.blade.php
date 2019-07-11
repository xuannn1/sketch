<li role="presentation" class="{{$status_tab==='follow'? 'active':''}}"><a href="{{ route('statuses.collections') }}">关注动态</a></li>
<li role="presentation" class="{{$status_tab==='all'? 'active':''}}"><a href="{{ route('statuses.index') }}">全站动态</a></li>
<li role="presentation" class="pull-right {{$status_tab==='user'? 'active':''}}"><a href="{{ route('users.index') }}">全部用户</a></li>
