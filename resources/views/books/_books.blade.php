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
          @if($show_as_collections==1)
          <button class="btn-xs sosad-button-ghost hidden cancel-button" type="button" name="button" onClick="cancelCollectionItem({{$book->thread_id}},1,0)">
              <i class="fa fa-trash"></i>
              取消收藏
          </button>
          <button class="btn-xs sosad-button-ghost hidden cancel-button" type="button" name="button" onClick="ToggleKeepUpdateThread({{$book->thread_id}})" id="togglekeepupdatethread{{$book->thread_id}}">
              <i class="fa fa-bell{{$book->keep_updated?'-slash':''}}"></i>
              {{$book->keep_updated?'不再提醒':'接收提醒'}}
          </button>
          <span class="button-group" style="position: relative;">
                <button class="btn-xs sosad-button-ghost hidden cancel-button" type="button" data-toggle="dropdown">
                    <i class="fa fa-plus"></i>
                    添加到收藏单
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-cancel">
                    @foreach($own_collection_book_lists as $list)
                    <li><a type="button" name="button" onClick="item_add_to_collection({{$book->thread_id}},1,{{$list->id}})">{{$list->title}}</a></li>
                    @endforeach
                </ul>
            </span>
          @elseif($show_as_collections==2)
          <button class="btn-xs sosad-button-ghost hidden cancel-button" type="button" name="button" onClick="cancelCollectionItem({{ $book->thread_id }},1,{{ $collection_list->id }})">取消收藏</button>
          <a class="btn-xs sosad-button-ghost hidden cancel-button" href="#" data-toggle="modal" data-target="#TriggerCollectionComment{{ $book->collection_id }}">添加/修改心得</a>
          <div class="modal fade" id="TriggerCollectionComment{{ $book->collection_id }}" role="dialog">
              <div class="modal-dialog">
                  <div class="modal-content">
                      <form action="{{ route('collection.store_comment', $book->collection_id)}}" method="POST">
                          {{ csrf_field() }}
                          <div class="form-group">
                              <textarea name="body" rows="6" class="form-control comment-editor" placeholder="留下对这个收藏的评论心得：" data-provide="markdown"  id="collectioncomment{{$book->collection_id}}">{{ $book->collection_body }}</textarea>
                              <button type="button" onclick="retrievecache('collectioncomment{{$book->collection_id}}')" class="sosad-button-ghost grayout">恢复数据</button>
                              <button href="#" type="button" class="pull-right sosad-button-ghost grayout">字数统计：<span id="collectioncomment{{$book->collection_id}}">0</span></button>
                          </div>
                          <div class="">
                              <button type="submit" class="pull-right sosad-button-post btn-sm">提交收藏心得</button>
                          </div>
                      </form>
                  </div>
              </div>
          </div>
          @endif
      </div>
      @if(($show_as_collections==2)&&($book->collection_body))
        <div class="thread-cancel">
            <span id="full{{$book->collection_id}}" class="hidden abbr">
                收藏心得：
                <span class="main-text smaller-10">
                    {!! Helper::wrapParagraphs($book->collection_body) !!}
                </span>
                <a type="button" name="button" id="expand{{$book->collection_id}}" onclick="expandpost('{{$book->collection_id}}')" >收起</a>
            </span>
            <span id="abbreviated{{$book->collection_id}}" class="abbr">
                收藏心得：
                <span class="grayout smaller-10">
                    {!! Helper::trimtext($book->collection_body,70) !!}
                </span>
                <a type="button" name="button" id="expand{{$book->collection_id}}" onclick="expandpost('{{$book->collection_id}}')" >展开</a>
            </span>
        </div>
      @endif
   </div>
</article>
@endforeach
