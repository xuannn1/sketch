@foreach($replies as $reply)
<article class="">
   <hr>
   <div class="row">
      <div class="col-xs-12">
         <span id="simple{{$reply->id}}"><em><a href="{{ route('thread.showpost', $reply->id) }}">
            {{ $reply->anonymous ? ($reply->majia ?? '匿名咸鱼'): $reply->name }}&nbsp;{{ Carbon\Carbon::parse($reply->created_at)->diffForHumans() }}回复了您的帖子&nbsp; {!! Helper::trimtext($reply->original_body,10) !!}</a></em></span>
      </div>
      <div class="col-xs-12">
         <span id="full{{$reply->id}}" class="hidden">
            <h5 class="text-center"><strong>{{ $reply->title }}</strong></h5>
            <div class="text-center">
               <span>
                  @if ($reply->anonymous)
                     {{ $reply->majia ?? '匿名咸鱼'}}
                  @else
                     <a href="{{ route('user.show', $reply->user_id) }}">{{ $reply->name }}</a>
                  @endif
               </span>
               <span class="grayout">
                  发表于 {{ Carbon\Carbon::parse($reply->created_at)->diffForHumans() }}
                  @if($reply->created_at < $reply->edited_at )
                    修改于 {{ Carbon\Carbon::parse($reply->edited_at)->diffForHumans() }}
                  @endif
               </span>
            </div>
            <div class="main-text">
               {!! Markdown::convertToHtml($reply->body) !!}
            </div>
            <?php $post = $reply ?>
            @include('posts._post_simplevote')
         </span>
         <span id="abbreviated{{$post->id}}" class=""><strong>{{ $post->title }}</strong>{{ $post->title ? ' ': ''}}{!! Helper::trimtext($reply->body,60) !!}</span>
         <small><a type="button" name="button" id="expand{{$post->id}}" onclick="expandpost('{{$post->id}}')" class="pull-right">展开</a></small>
      </div>
   </div>
</article>
@endforeach
