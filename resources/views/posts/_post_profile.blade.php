<span>
   @if ($post->maintext)
      @if ($thread->anonymous)
         <span>{{ $thread->majia ?? '匿名咸鱼'}}</span>
         @if((Auth::check()&&(Auth::user()->admin)))
         <span class="admin-anonymous"><a href="{{ route('user.show', $thread->user_id) }}">{{ $thread->creator->name }}</a></span>
         @endif
      @else
         <a href="{{ route('user.show', $post->user_id) }}">{{ $thread->creator->name }}</a>
      @endif
   @else
      @if ($post->anonymous)
         <span>{{ $post->majia ?? '匿名咸鱼'}}</span>
         @if((Auth::check()&&(Auth::user()->admin)))
         <span class="admin-anonymous"><a href="{{ route('user.show', $post->user_id) }}">{{ $post->owner->name }}</a></span>
         @endif
      @else
         <a href="{{ route('user.show', $post->user_id) }}">{{ $post->owner->name }}</a>
      @endif
   @endif
   @if((!$post->anonymous)&&((!$thread->anonymous)||(!$post->maintext)))
      @if(request('useronly'))
         <span class="grayout smaller-20"><a href="{{ route('thread.show',$thread) }}">取消只看该用户</a></span>
      @else
         <span class="grayout smaller-20"><a href="{{ route('thread.show', ['thread'=>$thread->id, 'useronly'=>$post->user_id]) }}">只看该用户</a></span>
      @endif
   @endif
   <span class="smaller-20">
      发表于 {{ Carbon\Carbon::parse($post->created_at)->diffForHumans() }}
      @if($post->created_at < $post->edited_at )
        修改于 {{ Carbon\Carbon::parse($post->edited_at)->diffForHumans() }}
      @endif
   </span>

  @if((Auth::check())&&(Auth::user()->admin))
  @include('admin._delete_post')
  @endif

</span>
