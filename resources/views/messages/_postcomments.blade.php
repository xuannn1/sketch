@foreach($postcomments as $postcomment)
<article class="">
   <hr>
   <div class="row">
      <div class="col-xs-12">
         <span id="simple{{$postcomment->id}}"><em><a href="{{ route('post.show', $postcomment->post_id) }}">
            {{ $postcomment->anonymous ? ($postcomment->majia ?? '匿名咸鱼'): $postcomment->name }}&nbsp;{{ Carbon\Carbon::parse($postcomment->created_at)->diffForHumans() }}点评了您的帖子&nbsp;
            {!! Helper::trimtext($postcomment->post_body,10) !!}
         </a></em></span>
      </div>
      <div class="col-xs-12">
         <span id="full{{$postcomment->id}}">
            {{ $postcomment->body }}
         </span>
      </div>
   </div>
</article>
@endforeach
