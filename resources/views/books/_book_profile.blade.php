<!-- 书本标题 -->
<div class="article-title text-center">
   <h2>
       @include('threads._thread_title')
       @if((Auth::check())&&(Auth::user()->admin))
       @include('admin._modify_thread')
       @endif
   </h2>
</div>

<div class="article-body text-center">
    <div>{{ Helper::convert_to_public($thread->brief) }}</div>
    <div>
        @include('threads._thread_author_time')
    </div>
    <!-- 书本基本信息 -->
    <div class="book-intro">
       <!-- 书本登记信息，同人信息，长度，连载进度etc -->
       <b>
       @if(!$thread->original())
       <p>
           @if($book->tongren->tongren_yuanzhu_tag_id>0)
           <a href="{{ route('books.booktag', $book->tongren->tongren_yuanzhu_tag_id) }}">《{{$book->tongren->yuanzhu()}}》</a>
           @else
           {{ $book->tongren->tongren_yuanzhu }}
           @endif
           -
           @if($book->tongren->tongren_CP_tag_id>0)
           <a href="{{ route('books.booktag', $book->tongren->tongren_CP_tag_id) }}">{{$book->tongren->cp()}}</a>
           @else
           {{ $book->tongren->tongren_cp }}
           @endif
        </p>
       @endif
       <em><p>
          <a href="{{ route('books.index', ['channel'=>(int)($book->channel_id)]) }}">{{ config('constants.book_info')['originality_info'][$thread->original()] }}</a>
          -&nbsp;<a href="{{ route('books.index',['book_length'=>$book->book_length]) }}">{{ config('constants.book_info')['book_lenth_info'][$book->book_length] }}</a>
          -&nbsp;<a href="{{ route('books.index',['book_status'=>$book->book_status]) }}">{{ config('constants.book_info')['book_status_info'][$book->book_status] }}</a>
          -&nbsp;<a href="{{ route('books.index',['sexual_orientation'=>$book->sexual_orientation]) }}">{{ config('constants.book_info')['sexual_orientation_info'][$book->sexual_orientation] }}</a>
       </p>
       <p>
          @if( $thread->bianyuan == 1)
             <span class="badge">边</span>
          @endif
          <a href="{{ route('books.index',['label'=>$thread->label_id]) }}">{{ $thread->label->labelname }}</a>
          @foreach ($thread->tags as $int=>$tag)
             - <a href="{{ route('books.booktag', $tag->id) }}">{{ $tag->tagname }}</a>
          @endforeach
       </p></em>
       </b>
    </div>
    <!-- 书本文案 -->
    <div class="panel-body text-center main-text">
       @if($thread->mainpost->markdown)
       {!! Helper::sosadMarkdown($thread->body) !!}
       @else
       {!! Helper::wrapParagraphs($thread->body) !!}
       @endif
    </div>
</div>
