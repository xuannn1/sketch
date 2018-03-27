@foreach($threads as $thread)
<article class="{{ 'thread'.$thread->id }}">
   <div class="row">
      <div class="col-xs-12 h5">
         @if($show_as_collections)
         <button class="btn btn-xs btn-danger sosad-button hidden cancel-button" type="button" name="button" onClick="cancelCollectionThread({{$thread->id}})">取消收藏</button>
         <button class="btn btn-xs btn-warning sosad-button hidden cancel-button" type="button" name="button" onClick="ToggleKeepUpdateThread({{$thread->id}})" Id="togglekeepupdatethread{{$thread->id}}">{{$thread->keep_updated?'不再提醒':'接收提醒'}}</button>
         @endif
         <!-- thread title -->
         <span class="bigger-20">
            <a class="btn btn-xs btn-success sosad-button" href="{{route('channel.show', $thread->channel_id)}}">{{$thread->channel->channelname}}</a>
            <a class="btn btn-xs btn-warning sosad-button" href="{{route('channel.show',['channel'=>$thread->channel_id,'label'=>$thread->label_id])}}">{{$thread->label->labelname}}</a>
            <strong><a href="{{ route('thread.show', $thread->id) }}">{{ $thread->title }}</a></strong>
            @if( $thread->bianyuan == 1)
            <span class="badge">边</span>
            @endif
            <small>
            @if(!$thread->public)
               <span class="glyphicon glyphicon-eye-close"></span>
            @endif
            @if($thread->locked)
               <span class="glyphicon glyphicon-lock"></span>
            @endif
            @if($thread->noreply)
            <span class="glyphicon glyphicon-warning-sign"></span>
            @endif
            </small>
            @if(($show_as_collections)&&($thread->updated))
            <span class="badge">有更新</span>
            @endif
         </span>
         <!-- thread title end   -->
         <!-- author  -->
         <span class = "pull-right">
             @if($thread->anonymous)
                <span>{{ $thread->majia ?? '匿名咸鱼'}}</span>
                @if((Auth::check()&&(Auth::user()->admin)))
                <span class="admin-anonymous"><a href="{{ route('user.show', $thread->creator->id) }}">{{ $thread->creator->name }}</a></span>
                @endif
             @else
                <a href="{{ route('user.show', $thread->creator->user_id) }}">{{ $thread->creator->name }}</a>
             @endif
         </span>
         <!-- author end -->
      </div>
      <div class="col-xs-12 h5 ">
         <span>{{ $thread->brief }}</span>
         <span class="pull-right smaller-10"><em><span class="glyphicon glyphicon-eye-open"></span>{{ $thread->viewed }}/<span class="glyphicon glyphicon glyphicon-comment"></span>{{ $thread->responded }}</em></span>
      </div>
      <div class="col-xs-12 h5 grayout brief">
         <span class="smaller-10"><a href="{{ route('thread.showpost', $thread->last_post_id) }}"> {!! Helper::trimtext($thread->lastpost->body,20) !!}</a></span>
         <span class="pull-right smaller-10">{{ Carbon\Carbon::parse($thread->created_at)->diffForHumans() }}/{{ Carbon\Carbon::parse($thread->lastresponded_at)->diffForHumans() }}</span>
      </div>
   </div>
   <hr>
</article>
@endforeach
