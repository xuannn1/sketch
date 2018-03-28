<div class="panel panel-default id = "post{{ $chapter->post_id }}">
   <div class="panel-heading">
       <div class="h5 text-center">
           <a href="{{route('book.showchapter', $chapter->id)}}" class="bigger-20">{{ $chapter->title }}</a>
       </div>
       <div class="grayout smaller-10 text-center">
          发表于 {{ Carbon\Carbon::parse($chapter->mainpost->created_at)->diffForHumans() }}
          @if($chapter->mainpost->created_at < $chapter->mainpost->edited_at )
            修改于 {{ Carbon\Carbon::parse($chapter->mainpost->edited_at)->diffForHumans() }}
          @endif
       </div>
   </div>
   <div class="panel-body post-body">
       <div class="text-left main-text {{ $chapter->mainpost->indentation? 'indentation':'' }}">
          @if($chapter->mainpost->markdown)
          {!! Helper::sosadMarkdown($chapter->mainpost->body) !!}
          @else
          {!! Helper::wrapParagraphs($chapter->mainpost->body) !!}
          @endif
          <br>
       </div>
       <div class="text-left grayout">
          {!! Helper::sosadMarkdown($chapter->annotation) !!}
       </div>
   </div>

   @if(Auth::check())
      <div class="text-right post-vote">
         @include('posts._post_vote')
      </div>
   @endif
</div>
