@foreach($posts as $post)
<article class="">
   <div class="row">
      <div class="thread-info">
         <span>
            <a href="{{ route('thread.showpost', $post->id) }}" class="thread-title">
            回复：{{ $post->thread_title }}</a>
         </span>
         <span class="smaller-15">
            <span>
                <a href="{{ route('thread.showpost', $post->id) }}">
                <em>回复：{{ $post->thread_title }}</a></em>
            </span>
         </span>
      </div>
      <div class="">
         <div id="full{{$post->id}}" class="hidden main-text">
            <h5 class="text-center"><strong>{{ $post->title }}</strong></h5>
            @if($post->markdown)
            {!! Helper::sosadMarkdown($post->body) !!}
            @else
            {!! Helper::wrapParagraphs($post->body) !!}
            @endif
         </div>
         <span id="abbreviated{{$post->id}}" class="smaller-10"><strong>{{ $post->title }}</strong>{{ $post->title ? ' ': ''}}{!! Helper::trimtext($post->body,60) !!}</span>
         <a type="button" name="button" id="expand{{$post->id}}" onclick="expandpost('{{$post->id}}')" class="grayout">展开</a>
         @include('posts._post_simplevote')
      </div>
      @if((Auth::check())&&(Auth::user()->admin)&&($as_longcomments))
        @include('admin._longcomments_review_buttons')
      @endif
   </div>
</article>
@endforeach
