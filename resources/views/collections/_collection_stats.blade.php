<li role="presentation">
    <a href="{{ route('collections.books') }}" class="{{$active==0? 'active':''}}">
        文章<span class="badge">{{$updates[0]!=0 ? $updates[0]:''}}</span>
    </a>
</li>
<li role="presentation">
    <a href="{{ route('collections.threads') }}" class="{{$active==1? 'active':''}}">
        讨论<span class="badge">{{$updates[1]!=0 ? $updates[1]:''}}</span>
    </a>
</li>
<li role="presentation">
    <a href="{{ route('collections.statuses') }}" class="{{$active==2? 'active':''}}">
        关注<span class="badge">{{$updates[2]!=0 ? $updates[2]:''}}</span>
    </a>
</li>
<li role="presentation">
    <a href="{{ route('collections.collection_lists') }}" class="{{$active==3? 'active':''}}">
        清单<span class="badge">{{$updates[3]!=0 ? $updates[3]:''}}</span>
    </a>
</li>
<li role="presentation">
    <a type="button" class="btn sosad-button-ghost grayout" id="cancelCollections" onClick="toggleCancelButtons()">
        <i class="fa fa-cog"></i>
        整理
    </a>
</li>
