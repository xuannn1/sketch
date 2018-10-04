@foreach($statuses as $status)
<article class="card status {{ 'status'.$status->id }} {{ 'followuser'.$status->user_id}}">
    <div class="panel-heading grayout smaller-10">
        <span>
            <a href="{{ route('user.show', $status->user_id) }}">{{ $status->name }}</a>&nbsp;
            {{ Carbon\Carbon::parse($status->created_at)->diffForHumans() }}
        </span>
        <span class="pull-right">
            @if ((Auth::check())&&($status->user_id != Auth::user()->id))
            <button type="button" class="sosad-button-ghost btn-xs {{'follow'.$status->user_id}} {{Auth::user()->isFollowing($status->user_id) ? 'hidden':''}}" onclick="follow({{$status->user_id}})">
                <i class="fa fa-plus"></i>
                关注
            </button>
            <button type="button" class="sosad-button-ghost btn-xs {{'cancelfollow'.$status->user_id}} {{Auth::user()->isFollowing($status->user_id) ? '':'hidden'}}" onclick="cancelfollow({{$status->user_id}})">
                <i class="fa fa-minus"></i>
                取消关注
            </button>
            @endif
            @if($show_as_collections)
            <button class="hidden sosad-button-ghost smaller-10 grayout {{'togglekeepupdateuser'.$status->user_id}}" type="button" name="button" onClick="ToggleKeepUpdateUser({{$status->user_id}})">{{$status->keep_updated? '不再提醒':'接收提醒'}}</button>
            @endif
        </span>
    </div>
    <div class="panel-body post-body">
        <div class="main-text">
            {!! Helper::wrapParagraphs($status->content) !!}
        </div>
    </div>
    @if((Auth::check())&&(Auth::id()==$status->user_id))
    <button type="button" name="button" class="sosad-button-ghost btn-xs grayout pull-right" onclick="destroystatus({{$status->id}})">删除</button>
    @endif
</article>
@endforeach
