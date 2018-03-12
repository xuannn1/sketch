@foreach($upvotes as $post)
<article class="">
   <div class="row">
      <div class="col-xs-12 h5">
         <span>
            <a href="{{ route('thread.showpost', $post->id) }}">
            <em>回复主题：{{ $post->thread_title }}</a></em>
         </span>
         <span class="pull-right smaller-15">
            <span>
            @if($post->anonymous)
               {{ $post->majia ?? '匿名咸鱼'}}
               @if((Auth::check())&&(Auth::user()->admin))
               <span class="admin-anonymous"><a href="{{ route('user.show', $post->user_id) }}">{{ $post->name }}</a></span>
               @endif
            @else
               <a href="{{ route('user.show', $post->user_id) }}">{{ $post->name }}</a>
            @endif
            </span>&nbsp;
            <span class="grayout">发表于{{ Carbon\Carbon::parse($post->created_at)->diffForHumans() }}
               @if($post->created_at < $post->edited_at)
               /修改于{{ Carbon\Carbon::parse($post->edited_at)->diffForHumans() }}
               @endif
            </span>
         </span>
      </div>
      <div class="col-xs-12 h5">
         <div id="full{{$post->id}}" class="hidden main-text">
            <h5 class="text-center"><strong>{{ $post->title }}</strong></h5>
            @if($post->markdown)
            {!! Helper::sosadMarkdown($post->body) !!}
            @else
            {!! Helper::wrapParagraphs($post->body) !!}
            @endif
         </div>
         <span id="abbreviated{{$post->id}}"><strong>{{ $post->title }}</strong>{{ $post->title ? ' ': ''}}{!! Helper::trimtext($post->body,60) !!}</span>
         <a type="button" name="button" id="expand{{$post->id}}" onclick="expandpost('{{$post->id}}')">展开</a>
         @include('posts._post_simplevote')
      </div>
   </div>
   <hr>
</article>
@endforeach
