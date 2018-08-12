@foreach($books as $book)
<article class="{{ 'item1id'.$book->thread_id }} margin5">
   <div class="row thread">
      <div class="thread-info">
         <span class="thread-title">
            <strong>
               <span>
                  <a href="{{ route('book.show', $book->book_id) }}">
                  {{ $book->title }}</a>
                  @if( $book->bianyuan == 1)
                  <span class="badge">边</span>
                  @endif
               </span>
            </strong>
            <small>
                @if(!$book->public)
                <span class="glyphicon glyphicon-eye-close"></span>
                @endif
                @if($book->locked)
                <span class="glyphicon glyphicon-lock"></span>
                @endif
                @if($book->noreply)
                <span class="glyphicon glyphicon-warning-sign"></span>
                @endif
                @if(($book->last_chapter_title)&&($book->last_chapter_responded==0))
                <span class="badge">新</span>
                @endif
            </small>
         </span>
         @if(($show_as_collections)&&($book->updated))
         <span class="badge">有更新</span>
         @endif

        <span class="smaller-15">{{ $book->brief }}</span>

        <span class="smaller-10 grayout margin5"><a href="{{ route('book.showchapter', $book->last_chapter_id) }}">最新章节：{{ $book->last_chapter_title }}</a></span>

        <span class="smaller-10">
            <a href="{{ route('books.index',['channel'=>(int)($book->channel_id)]) }}" class="sosad-button-tag">
                {{ config('constants.book_info')['originality_info'][2-$book->channel_id] }}
            </a>
            <a href="{{ route('books.index',['book_length'=>$book->book_length]) }}" class="sosad-button-tag">
                {{ config('constants.book_info')['book_lenth_info'][$book->book_length] }}
            </a>
            <a href="{{ route('books.index',['book_status'=>$book->book_status]) }}" class="sosad-button-tag">{{ config('constants.book_info')['book_status_info'][$book->book_status] }}</a>
            <a href="{{ route('books.index',['label'=>$book->label_id]) }}" class="sosad-button-tag">
                {{ $book->labelname }}
            </a>
            <a href="{{ route('books.index',['sexual_orientation'=>$book->sexual_orientation]) }}" class="sosad-button-tag">
                {{ config('constants.book_info')['sexual_orientation_info'][$book->sexual_orientation] }}
            </a>
        </span>

      </div>
      <div class="thread-meta grayout smaller-10">
          <span class = "">
              @if($book->anonymous)
              <span>{{ $book->majia ?? '匿名咸鱼'}}</span>
              @if((Auth::check()&&(Auth::user()->admin)))
              <span class="admin-anonymous"><a href="{{ route('user.show', $book->user_id) }}">{{ $book->name }}</a></span>
              @endif
              @else
              <a href="{{ route('user.show', $book->user_id) }}">{{ $book->name }}</a>
              @endif
          </span>
         <span>
             <span class="glyphicon glyphicon-pencil"></span>
             {{ $book->total_char }}
             /
             <span class="glyphicon glyphicon-eye-open"></span>
             {{ $book->viewed }}
             /
             <span class="glyphicon glyphicon glyphicon-comment"></span>
             {{ $book->responded }}
         </span>
      </div>
      <div class="thread-cancel">
          @if($show_as_collections)
          <button class="btn-xs sosad-button-ghost hidden cancel-button" type="button" name="button" onClick="cancelCollectionItem({{$book->thread_id}},1,0)">
              <i class="fa fa-trash"></i>
              取消收藏
          </button>
          <button class="btn-xs sosad-button-ghost hidden cancel-button" type="button" name="button" onClick="ToggleKeepUpdateThread({{$book->thread_id}})" id="togglekeepupdatethread{{$book->thread_id}}">
              <i class="fa fa-bell{{$book->keep_updated?'-slash':''}}"></i>
              {{$book->keep_updated?'不再提醒':'接收提醒'}}
          </button>
          @endif
      </div>
   </div>
</article>
@endforeach
