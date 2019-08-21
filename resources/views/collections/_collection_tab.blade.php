<li role="presentation" class="{{$show_collection_tab==='default'? 'active':''}}"><a href="{{ route('collection.index') }}">默认收藏<span class="badge badge-tag">{{ $default_collection_updates>0?$default_collection_updates:'' }}</span></a></li>
@foreach($groups as $group)
<li role="presentation" class="{{$show_collection_tab==$group->id? 'active':''}}"><a href="{{ route('collection.index', ['group'=>$group->id]) }}">{{ $group->name }}<i class="fa fa-heart recommend-star {{ $info->default_collection_group_id==$group->id?'':'hidden' }}" aria-hidden="true"></i><span class="badge badge-tag">{{ $group->update_count>0? $group->update_count:''}}</span></a></li>
@endforeach
<li role="presentation" class="{{$show_collection_tab==='create'? 'active':''}}"><a href="{{ route('collection_group.create') }}"><i class="fa fa-plus" aria-hidden="true"></i></span></a></li>
<li role="presentation" class="pull-right"><a href="{{route('collection.clearupdates')}}" class="btn btn-md btn-primary sosad-button">全部标记已读</a></li>
