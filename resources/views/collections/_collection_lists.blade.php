@foreach($collection_lists as $collection_list)
<article class="{{ 'item4id'.$collection_list->id }}">
    <div class="row">
        <div class="col-xs-12 h5">
            @if($show_as_collections==1)
            <button class="btn btn-xs btn-danger sosad-button hidden cancel-button" type="button" name="button" onClick="cancelCollectionItem({{ $collection_list->id }},4,{{ $collected_list->id }})">取消收藏</button>
            @endif
            <!-- list title -->
            <span>
                <a class="btn btn-xs btn-success sosad-button tag-button-left tag-red" >{{ config('constants.collection_type_info')[$collection_list->type] }}</a>
                <span class="bigger-20"><strong><a href="{{ route('collections.collection_list_show', $collection_list->id) }}">
                    {{ $collection_list->title }}
                </a></strong></span>
                @if($collection_list->updated)
                <span class="badge newchapter-badge badge-tag">有更新</span>
                @endif
                <small>
                    @if($collection_list->private)
                    <span class="glyphicon glyphicon-eye-close"></span>
                    @endif
                </small>
            </span>
            <!-- list title end   -->
            <!-- author  -->
            <span class = "pull-right">
                @if ($collection_list->anonymous)
                <span>{{ $collection_list->majia ?? '匿名咸鱼'}}</span>
                @if((Auth::check()&&(Auth::user()->admin)))
                <span class="admin-anonymous"><a href="{{ route('user.show', $collection_list->user_id) }}">{{ $collection_list->creator->name }}</a></span>
                @endif
                @else
                <a href="{{ route('user.show', $collection_list->user_id) }}">{{ $collection_list->creator->name }}</a>
                @endif
            </span>
            <!-- author end -->
        </div>
        <div class="col-xs-12 h5 ">
            <span>{{ $collection_list->brief }}</span>
            <span class="pull-right smaller-10"><em><span class="glyphicon glyphicon-eye-open"></span>{{ $collection_list->viewed }}/<span class="glyphicon glyphicon glyphicon-heart"></span>{{ $collection_list->collected }}/<span class="glyphicon glyphicon-duplicate"></span>{{ $collection_list->item_number }}</em></span>
        </div>
        <div class="col-xs-12 h5 grayout brief">
            <span class="smaller-10"><a href="">{!! Helper::trimtext($collection_list->body,60) !!}</a></span>
            <span class="pull-right smaller-10">{{ Carbon\Carbon::parse($collection_list->created_at)->diffForHumans() }}/{{ Carbon\Carbon::parse($collection_list->lastupdated_at)->diffForHumans() }}</span>
        </div>
    </div>
    <hr>
</article>
@endforeach
