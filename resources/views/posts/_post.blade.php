<div class="panel panel-default" id = "post{{ $post->id }}">
   <div class="panel-heading">
      <div class="row">
         <div class="col-xs-12">
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
                  @if($only)
                     <span class="grayout smaller-20"><a href="{{ route('thread.show',$thread) }}">取消只看该作者</a></span>
                  @else
                     <span class="grayout smaller-20"><a href="{{ route('thread.useronly', ['thread'=>$thread->id, 'user'=>$post->user_id]) }}">只看该用户</a></span>
                  @endif
               @endif
               <span class="smaller-20">
                  发表于 {{ Carbon\Carbon::parse($post->created_at)->diffForHumans() }}
                  @if($post->created_at < $post->edited_at )
                    修改于 {{ Carbon\Carbon::parse($post->edited_at)->diffForHumans() }}
                  @endif
               </span>
            </span>
            <span class="pull-right">
               <a href="{{ route('thread.showpost', $post) }}">No.{{ ($posts->currentPage()-1)*$posts->perPage()+$key+1 }}</a>
            </span>
         </div>
      </div>

   </div>
   <div class="panel-body post-body">
      <div class="text-center">
         @if((Auth::check())&&(Auth::user()->admin))
         @include('admin._delete_post')
         @endif
      </div>
      @if($post->reply_to_post_id!=0)
      <div class="post-reply grayout">
         回复&nbsp;<a href="{{ route('thread.showpost', $post->reply_to_post_id) }}">{{ $post->reply_to_post->anonymous ? ($post->reply_to_post->majia ?? '匿名咸鱼') : $post->reply_to_post->owner->name }}&nbsp;{{ $post->reply_to_post->trim($post->reply_to_post->title . $post->reply_to_post->body, 20) }}</a>
      </div>
      @elseif(($post->chapter_id!=0)&&(!$post->maintext)&&($chapter_replied)&&($post->chapter->mainpost->id>0))
      <div class="post-reply grayout">
         评论&nbsp;<a href="{{ route('book.showchapter', $post->chapter_id) }}">{{ $post->trim( $post->chapter->title . $post->chapter->mainpost->title . $post->chapter->mainpost->body , 20) }}</a>
      </div>
      @endif
      <div class="main-text {{ $post->indentation? 'indentation':'' }}">
         @if($post->maintext)
         <div class="text-center h5">
         <strong>{{ $post->chapter->title }}</strong>
         <p class="grayout smaller-10">{{ $post->title }}</p>
         </div>
         <div>
            @if($post->markdown)
            {!! Markdown::convertToHtml($post->body) !!}
            @else
            {!! Helper::wrapParagraphs($post->body) !!}
            @endif
            <br>
         </div>
         <div class="grayout">
           <br>
            {!! Markdown::convertToHtml($post->chapter->annotation) !!}
         </div>
         <br>
         <div class="container-fluid">
            <u><a class="smaller-10" href="{{ route('book.showchapter', $post->chapter_id) }}">前往文库阅读</a></u>
            <span class="pull-right smaller-20"><em><span class="glyphicon glyphicon-pencil"></span>{{ $post->chapter->characters }}/<span class="glyphicon glyphicon-eye-open"></span>{{ $post->chapter->viewed }}/<span class="glyphicon glyphicon glyphicon-comment"></span>{{ $post->chapter->responded }}</em></span>
         </div>
         @else
            @if($post->title)
               <strong>{{ $post->title }}</strong>
            @endif
            @if($post->body && $post->markdown)
            {!! Markdown::convertToHtml($post->body) !!}
            @else
            {!! Helper::wrapParagraphs($post->body) !!}
            @endif
         @endif
      </div>
   </div>

   @if(Auth::check())
      <div class="text-right post-vote">
         @include('posts._post_vote')
      </div>
   @endif

   @if ($post->comments->count() > 0)
   <div class="panel-footer">
      <?php $postcomments = $post->comments; ?>
      @include('posts._post_comments')
   </div>
   @endif
</div>
