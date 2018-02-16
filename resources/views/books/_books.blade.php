@foreach($books as $book)
<article class="{{ 'thread'.$book->thread_id }}">
   <div class="row">
      <div class="col-xs-12 h5 narrow">
         @if($collections)
         <button class="btn btn-xs btn-danger sosad-button hidden cancel-button" type="button" name="button" onClick="cancelCollectionThread({{$book->thread_id}})">取消收藏</button>
         <button class="btn btn-xs btn-warning sosad-button hidden cancel-button" type="button" name="button" onClick="ToggleKeepUpdateThread({{$book->thread_id}})" Id="togglekeepupdatethread{{$book->thread_id}}">{{$book->keep_updated?'不再提醒':'接收提醒'}}</button>
         @endif
         <span class="bigger-20">
            <strong>
               <span>
                  <a href="{{ route('book.show', $book->book_id) }}">
                  {{ $book->title }}</a>
                  @if( $book->bianyuan == 1)
                  <span class="badge">边</span>
                  @endif
               </span>
            </strong>
         </span>
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
         </small>
         @if(($collections)&&($book->updated))
         <span class="badge">有更新</span>
         @endif
         <span class = "pull-right">
            @if($book->anonymous)
               <span>{{ $book->majia ?? '匿名咸鱼'}}</span>
               @if((Auth::check()&&(Auth::user()->admin)))
               <span class="admin-anonymous"><a href="{{ route('user.show', $book->user_id) }}">{{ $book->name }}</a></span>
               @endif
            @else
            <a href="{{ route('user.show', $book->user_id) }}">{{ $book->name }}</a>
            @endif
         </span>
      </div>
      <div class="col-xs-12 h5 brief">
         <span>{{ $book->brief }}</span>
         <span class = "pull-right smaller-10"><em><span class="glyphicon glyphicon-pencil"></span>{{ $book->total_char }}/<span class="glyphicon glyphicon-eye-open"></span>{{ $book->viewed }}/<span class="glyphicon glyphicon glyphicon-comment"></span>{{ $book->responded }}</em></span>
      </div>
      <div class="col-xs-12 h5 brief">
         <span class="grayout smaller-10"><a href="{{ route('book.showchapter', $book->last_chapter_id) }}">{{ $book->last_chapter_title }}</a></span>
         <span class="pull-right smaller-10"><em>
            <a href="{{ route('books.original',intval($book->original)) }}">{{ $book_info['originality_info'][$book->original] }}</a>-<a href="{{ route('books.booklength',$book->book_length) }}">{{ $book_info['book_lenth_info'][$book->book_length] }}</a>-<a href="{{ route('books.bookstatus',$book->book_status) }}">{{ $book_info['book_status_info'][$book->book_status] }}</a>-<a href="{{ route('books.booklabel',$book->label_id) }}">{{ $book->labelname }}</a>-<a href="{{ route('books.booksexualorientation',$book->sexual_orientation) }}">{{ $book_info['sexual_orientation_info'][$book->sexual_orientation] }}</a>
         </em></span>
      </div>
   </div>
   <hr class="narrow">
</article>
@endforeach
