<!-- 书本标题 -->
<div class="article-title text-center">
    <h2>
        @include('threads._thread_title')
        @if((Auth::check())&&(Auth::user()->admin))
        @include('admin._modify_thread')
        @endif
    </h2>
</div>

<div class="article-body">
    <div class="margin5 text-center">{{ $thread->brief }}</div>
    <div class="text-center">
        @include('threads._thread_author_time')
    </div>
    <!-- 书本基本信息 -->
    <div class="book-intro text-center">
        <!-- 书本登记信息，同人信息，长度，连载进度etc -->
            @if(!$thread->original())
            <div>
                @if($book->tongren->tongren_yuanzhu_tag_id>0)
                <a href="{{ route('books.booktag', $book->tongren->tongren_yuanzhu_tag_id) }}" class="sosad-button-tag{{$book->tongren->tongren_CP_tag_id>0?'-left':''}}">《{{$book->tongren->yuanzhu()}}》</a>
                @else
                {{ $book->tongren->tongren_yuanzhu }}
                @endif

                @if($book->tongren->tongren_CP_tag_id>0)
                <a href="{{ route('books.booktag', $book->tongren->tongren_CP_tag_id) }}" class="sosad-button-tag{{$book->tongren->tongren_yuanzhu_tag_id>0?'-right':''}}">{{$book->tongren->cp()}}</a>
                @else
                {{ $book->tongren->tongren_cp }}
                @endif
            </div>
            @endif
            <div>
                <a href="{{ route('books.index', ['channel'=>(int)($book->channel_id)]) }}" class="sosad-button-tag">{{ config('constants.book_info')['channel_info'][$thread->channel_id] }}</a>
                &nbsp;<a href="{{ route('books.index',['book_length'=>$book->book_length]) }}" class="sosad-button-tag">{{ config('constants.book_info')['book_lenth_info'][$book->book_length] }}</a>
                &nbsp;<a href="{{ route('books.index',['book_status'=>$book->book_status]) }}" class="sosad-button-tag">{{ config('constants.book_info')['book_status_info'][$book->book_status] }}</a>
                &nbsp;<a href="{{ route('books.index',['sexual_orientation'=>$book->sexual_orientation]) }}" class="sosad-button-tag">{{ config('constants.book_info')['sexual_orientation_info'][$book->sexual_orientation] }}</a>

                @if( $thread->bianyuan == 1)
                <span class="badge">限</span>
                @endif
                <br>
                <a href="{{ route('books.index',['label'=>$thread->label_id]) }}" class="sosad-button-tag">{{ $thread->label->labelname }}</a>
                @foreach ($thread->tags as $int=>$tag)
                &nbsp;<a href="{{ route('books.booktag', $tag->id) }}" class="sosad-button-tag">{{ $tag->tagname }}</a>
                @endforeach
            </div>
            <div class="">
              <span class="">
                <!-- 这个地方，是整本书的信息汇总：总字数，阅读数，回应数，下载数 -->
                <span class = "smaller-10"><span class="glyphicon glyphicon-pencil"></span>&nbsp;{{ $book->total_char }} / <span class="glyphicon glyphicon-eye-open"></span>&nbsp;{{ $thread->viewed }} / <span class="glyphicon glyphicon-comment"></span>&nbsp;{{ $thread->responded }} / <span class="glyphicon glyphicon-save"></span>{{ $thread->downloaded }}&nbsp;&nbsp;<span>
                </span>
            </div>
    </div>
    <!-- 书本文案 -->
    <div class="text-center grayout main-text">
        @if(($thread->bianyuan)&&(!Auth::check())&&($thread->mainpost->body))
        <div class="text-center">
            <h6 class="display-4"><a href="{{ route('login') }}">本文为限制级，文案暂时隐藏，只对注册用户开放，请登录后查看</a></h6>
        </div>
        @else
            @if($thread->mainpost->markdown)
            {!! Helper::sosadMarkdown($thread->mainpost->body) !!}
            @else
            {!! Helper::wrapParagraphs($thread->mainpost->body) !!}
            @endif
        @endif
    </div>
    <div class="text-center">
      <span>
        @if($show_as_book)
        <a href="{{ route('thread.show', $thread) }}" class="sosad-button-tag">
          <i class="fa fa-comment"></i>
          论坛讨论模式
        </a>
        @else
        <a href="{{ route('book.show', $book) }}" class="sosad-button-tag">
          <i class="fa fa-book"></i>
          文库阅读模式
        </a>
        @endif
      </span>

      </div>
</div>
