@foreach($threads as $thread)
<article class="{{ 'item2id'.$thread->thread_id }}">
   <div class="row thread">
      <div class="thread-info">
         <!-- thread title -->
         <span class="thread-title">
             @if($show_channel)
                <a class="btn-xs sosad-button-tag-left" href="{{route('channel.show', $thread->channel_id)}}">
                    {{$thread->channelname}}
                </a>
                <a class="btn-xs sosad-button-tag-right" href="{{route('channel.show',['channel'=>$thread->channel_id,'label'=>$thread->label_id])}}">{{$thread->labelname}}</a>
            @else
                <a class="btn-xs sosad-button-tag" href="{{route('channel.show',['channel'=>$thread->channel_id,'label'=>$thread->label_id])}}">{{$thread->labelname}}</a>
            @endif
            <a href="{{ route('thread.show', $thread->id) }}">{{ $thread->title }}</a>
            @if($thread->channel_id==2)
                @if($thread->tongren_yuanzhu_tagname)
                <a class="btn-xs sosad-button-tag{{$thread->tongren_cp_tagname ? '-left' : ''}}" href="{{ route('books.booktag', $thread->tongren_yuanzhu_tag_id) }}">{{$thread->tongren_yuanzhu_tagname}}</a>
                @endif
                @if($thread->tongren_cp_tagname)
                <a class="btn-xs sosad-button-tag{{$thread->tongren_cp_tagname ? '-right' : ''}}" href="{{ route('books.booktag', $thread->tongren_cp_tag_id) }}">{{$thread->tongren_cp_tagname}}</a>
                @endif
            @endif
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
          </span>&nbsp;&nbsp;
          <!-- author end -->
          <!-- time -->
          <div class="brief">
             <span>{{ Carbon\Carbon::parse($thread->created_at)->diffForHumans() }}/{{ Carbon\Carbon::parse($thread->lastresponded_at)->diffForHumans() }}</span>
          </div>&nbsp;&nbsp;

          <!-- viewed/replied -->
          <span>
            <span class="glyphicon glyphicon-eye-open"></span>
            {{ $thread->viewed }}
            /
            <span class="glyphicon glyphicon glyphicon-comment"></span>
            {{ $thread->responded }}
          </span>
      </div>
      <div class="thread-cancel">
          @if($show_as_collections)
          <button class="btn-xs sosad-button-ghost hidden cancel-button" type="button" name="button" onClick="cancelCollectionItem({{$thread->thread_id}},2,0)">取消收藏</button>
          <button class="btn-xs sosad-button-ghost hidden cancel-button" type="button" name="button" onClick="ToggleKeepUpdateThread({{$thread->thread_id}})" id="togglekeepupdatethread{{$thread->thread_id}}">{{$thread->keep_updated?'不再提醒':'接收提醒'}}</button>
          @endif
      </div>
   </div>
   <!-- <hr> -->
</article>
@endforeach
