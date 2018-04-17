@foreach($statuses as $status)
<article class="{{ 'status'.$status->id }} {{ 'followuser'.$status->user_id}}">
   <div class="row">
      <div class="col-xs-12 h5">
         @if($show_as_collections)
         <button type="button" class="btn btn-xs btn-danger sosad-button hidden cancel-button" onclick="cancelfollow({{$status->user_id}})">取消关注</button>
         <button class="btn btn-xs btn-warning sosad-button hidden cancel-button {{'togglekeepupdateuser'.$status->user_id}}" type="button" name="button" onClick="ToggleKeepUpdateUser({{$status->user_id}})">{{$status->keep_updated? '不再提醒':'接收提醒'}}</button>
         @endif
         <span>
            <a href="{{ route('user.show', $status->user_id) }}">{{ $status->name }}</a>&nbsp;
            {{ Carbon\Carbon::parse($status->created_at)->diffForHumans() }}
         </span>
         @if((Auth::check())&&(Auth::id()==$status->user_id))
         <button type="button" name="button" class="sosad-button btn btn-xs btn-danger pull-right" onclick="destroystatus({{$status->id}})">删除动态</button>
         @endif
      </div>
      <div class="col-xs-12 h5 brief">
         <span class="smaller-10">
            {!! Helper::wrapParagraphs($status->content) !!}
         </span>
      </div>
   </div>
   <hr class="narrow">
</article>
@endforeach
