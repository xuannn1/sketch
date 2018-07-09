<li role="presentation" class="{{$active==1? 'active':''}}"><a href="{{ route('threads.index') }}">全部讨论帖</a></li>
<li role="presentation" class="{{$active==2? 'active':''}}"><a href="{{ route('collection_lists.index') }}">全部收藏单</a></li>
<li role="presentation" class="pull-right {{$active==0? 'active':''}}"><a href="{{ route('longcomments.index') }}">全部长评</a></li>
