<li role="presentation" class="{{$active==0? 'active':''}}"><a href="{{ route('collections.books') }}">文章<span class="badge">{{$updates[0]!=0 ? $updates[0]:''}}</span></a></li>
<li role="presentation" class="{{$active==1? 'active':''}}"><a href="{{ route('collections.threads') }}">讨论<span class="badge">{{$updates[1]!=0 ? $updates[1]:''}}</span></a></li>
<li role="presentation" class="{{$active==2? 'active':''}}"><a href="{{ route('collections.statuses') }}">关注<span class="badge">{{$updates[2]!=0 ? $updates[2]:''}}</span></a></li>
<li role="presentation" class="pull-right"><a type="button" class="btn btn-danger sosad-button" id="cancelCollections" onClick="toggleCancelButtons()">整理</a></li>
