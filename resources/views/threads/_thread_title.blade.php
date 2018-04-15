<span>
   <a href="{{ route('thread.show',$thread->id) }}">{{ $thread->title }}</a><small>
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
</span>
