<article class="{{ 'thread'.$thread->id }}">
   <div class="row thread">
      <div class="thread-info">
         <span>
            <a href="{{ route('thread.show', $thread->id) }}" class="bigger-10">{{ $thread->title }}</a>
         </span>
         <span class="grayout smaller-15">{{ $thread->brief }}</span>
         <!-- <span id = "thread-info" class = "pull-right"> -->
      </div>
      <div class="grayout smaller-15 thread-meta">
          <span >
              @if($thread->anonymous)
                 <span>{{ $thread->majia ?? '匿名咸鱼'}}</span>
                 @if((Auth::check()&&(Auth::user()->admin)))
                 <span class="admin-anonymous"><a href="{{ route('user.show', $thread->user_id) }}">{{ $thread->creator->name }}</a></span>
                 @endif
              @else
                 <a href="{{ route('user.show', $thread->user_id) }}">{{ $thread->creator->name }}</a>
              @endif

           </span>
           <span>
               &emsp;
               {{ Carbon\Carbon::parse($thread->created_at)->diffForHumans() }}／
               {{ Carbon\Carbon::parse($thread->lastresponded_at)->diffForHumans() }}
           </span>
      </div>
   </div>
</article>
