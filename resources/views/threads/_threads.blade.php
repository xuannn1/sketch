@foreach($threads as $thread)
<article class="{{ 'thread'.$thread->id }}">
   <div class="row thread">
      <div class="thread-info">
         @if($show_as_collections)
         <button class="btn-xs sosad-button hidden cancel-button" type="button" name="button" onClick="cancelCollectionThread({{$thread->id}})">取消收藏</button>
         <button class="btn-xs sosad-button hidden cancel-button" type="button" name="button" onClick="ToggleKeepUpdateThread({{$thread->id}})" Id="togglekeepupdatethread{{$thread->id}}">{{$thread->keep_updated?'不再提醒':'接收提醒'}}</button>
         @endif
         <!-- thread title -->
         <span class="thread-title">
            <a class="btn-xs sosad-button-tag" href="{{route('channel.show',['channel'=>$thread->channel_id,'label'=>$thread->label_id])}}">{{$thread->labelname}}</a>
            <a href="{{ route('thread.show', $thread->id) }}">{{ $thread->title }}</a>
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
         <!-- brief -->
         <span class="brief smaller-10">
             {{ $thread->brief }}
         </span>
         <span class="grayout smaller-10"><a href="{{ route('thread.showpost', $thread->last_post_id) }}"> {!! Helper::trimtext($thread->last_post_body,20) !!}</a></span>
      </div>
      <div class="thread-meta smaller-10">
          <!-- author  -->
          <span>
              @if($thread->anonymous)
                 <span>{{ $thread->majia ?? '匿名咸鱼'}}</span>
                 @if((Auth::check()&&(Auth::user()->admin)))
                 <span class="admin-anonymous"><a href="{{ route('user.show', $thread->user_id) }}">{{ $thread->name }}</a></span>
                 @endif
              @else
                 <a href="{{ route('user.show', $thread->user_id) }}">{{ $thread->name }}</a>
              @endif
          </span>
          <!-- author end -->
          <!-- time -->
          <div class="brief">
             <span>{{ Carbon\Carbon::parse($thread->created_at)->diffForHumans() }}/{{ Carbon\Carbon::parse($thread->lastresponded_at)->diffForHumans() }}</span>
          </div>

          <!-- viewed/replied -->
          <span class="loose"><span class="glyphicon glyphicon-eye-open"></span>{{ $thread->viewed }}/<span class="glyphicon glyphicon glyphicon-comment"></span>{{ $thread->responded }}</span>
      </div>
   </div>
   <!-- <hr> -->
</article>
@endforeach
