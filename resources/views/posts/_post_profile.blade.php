<span>
    <!-- 显示作者名称 -->
   @if ($post->maintext)
      @if ($thread->anonymous)
         <span class="smaller-10">{{ $thread->majia ?? '匿名咸鱼'}}</span>
         @if((Auth::check()&&(Auth::user()->admin)))
         <span class="admin-anonymous smaller-10"><a href="{{ route('user.show', $thread->user_id) }}">{{ $thread->creator->name }}</a></span>
         @endif
      @else
         <a class="smaller-10" href="{{ route('user.show', $post->user_id) }}">{{ $thread->creator->name }}</a>
      @endif
   @else
      @if ($post->anonymous)
         <span class="smaller-10">{{ $post->majia ?? '匿名咸鱼'}}</span>
         @if((Auth::check()&&(Auth::user()->admin)))
         <span class="admin-anonymous smaller-10"><a href="{{ route('user.show', $post->user_id) }}">{{ $post->owner->name }}</a></span>
         @endif
      @else
         <a href="{{ route('user.show', $post->user_id) }}">{{ $post->owner->name }}</a>
      @endif
   @endif

   @if((!$post->anonymous)&&((!$thread->anonymous)||(!$post->maintext)))
      <!-- 只看该用户/取消只看该用户-标志 -->
      @if(request('useronly'))
         <span class="grayout smaller-20"><a href="{{ route('thread.show',$thread) }}">取消只看该用户</a></span>
      @else
         <span class="grayout smaller-20 sosad-button-tag"><a href="{{ route('thread.show', ['thread'=>$thread->id, 'useronly'=>$post->user_id]) }}">只看该用户</a></span>
      @endif
   @endif
   <!-- 发表时间 -->
   <span class="smaller-20 grayout">
      发表于 {{ Carbon\Carbon::parse($post->created_at)->diffForHumans() }}
      @if($post->created_at < $post->edited_at )
        修改于 {{ Carbon\Carbon::parse($post->edited_at)->diffForHumans() }}
        @endif
    </span>

    @if((Auth::check())&&(Auth::user()->admin))
    @include('admin._delete_post')
    @endif

</span>
