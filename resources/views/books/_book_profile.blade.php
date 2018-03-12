<div class="article-title text-center">
   <h2><a href="{{ route('book.show', $thread->book_id) }}">{{ $thread->title }}</a><small>
   @if(!$thread->public)
   <span class="glyphicon glyphicon-eye-close"></span>
   @endif
   @if($thread->locked)
   <span class="glyphicon glyphicon-lock"></span>
   @endif
   @if($thread->noreply)
   <span class="glyphicon glyphicon-warning-sign"></span>
   @endif
   </small></h2>
   @if((Auth::check())&&(Auth::user()->admin))
   @include('admin._modify_thread')
   @endif
</div>
<div class="book-intro text-center">
   @if ($thread->anonymous)
      <p>{{ $thread->majia ?? '匿名咸鱼'}}</p>
      @if((Auth::check()&&(Auth::user()->admin)))
      <p class="admin-anonymous"><a href="{{ route('user.show', $thread->user_id) }}">{{ $thread->creator->name }}</a></p>
      @endif
   @else
      <p><a href="{{ route('user.show', $thread->user_id) }}">{{ $thread->creator->name }}</a></p>
   @endif
   <!-- 发表时间 -->
   <p class = "grayout">发表于 {{ Carbon\Carbon::parse($thread->created_at)->diffForHumans() }}
   @if($thread->created_at < $thread->edited_at)
     修改于 {{ Carbon\Carbon::parse($thread->edited_at)->diffForHumans() }}
   @endif
   </p>
   <p>{{ $thread->brief }}</p>
   <em><b>
   @if(!$book->original)
   <p>{{ $book->tongren->tongren_yuanzhu }}-{{ $book->tongren->tongren_cp }}</p>
   @endif
   <p>
      <a href="{{ route('books.original', intval($book->original)) }}">{{ $book_info['originality_info'][$book->original] }}</a>
      -&nbsp;<a href="{{ route('books.booklength',$book->book_length) }}">{{ $book_info['book_lenth_info'][$book->book_length] }}</a>
      -&nbsp;<a href="{{ route('books.bookstatus',$book->book_status) }}">{{ $book_info['book_status_info'][$book->book_status] }}</a>
      -&nbsp;<a href="{{ route('books.booksexualorientation',$book->sexual_orientation) }}">{{ $book_info['sexual_orientation_info'][$book->sexual_orientation] }}</a>
   </p>
   <p>
      @if( $thread->bianyuan == 1)
         <span class="badge">边</span>
      @endif
      <a href="{{ route('books.booklabel',$thread->label_id) }}">{{ $thread->label->labelname }}</a>
      @foreach ($thread->tags as $int=>$tag)
         - <a href="{{ route('books.booktag', $tag->id) }}">{{ $tag->tagname }}</a>
      @endforeach
   </p>
   </b></em>
</div>

<div class="panel-body text-center main-text">
   @if($thread->mainpost->markdown)
   {!! Helper::sosadMarkdown($thread->body) !!}
   @else
   {!! Helper::wrapParagraphs($thread->body) !!}
   @endif
   <br>
</div>
