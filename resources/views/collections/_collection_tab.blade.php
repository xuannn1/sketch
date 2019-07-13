<li role="presentation" class="{{$show_collection_tab==='default'? 'active':''}}"><a href="#">文章</span></a></li>
<li role="presentation" class="{{$show_collection_tab==='create'? 'active':''}}"><a href="#"><i class="fa fa-plus" aria-hidden="true"></i></span></a></li>
@foreach($groups as $group)
<li role="presentation" class="{{$show_collection_tab==$group->id? 'active':''}}"><a href="#">{{ $group->title }}</span></a></li>
@endforeach
