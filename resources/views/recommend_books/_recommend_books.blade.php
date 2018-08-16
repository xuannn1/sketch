@foreach($recommend_books as $recommend_book)
<article class="{{ 'recommend_book'.$recommend_book->id }}">
    <div class="row">
        <div class="col-xs-12 h5">
            <!-- 帖子的相关信息 -->
            <span>
              <span class="bigger-20">
                  <strong>
                      <span>
                          <a href="{{ route('book.show', $recommend_book->book_id) }}">
                              {{ $recommend_book->title }}
                          </a>
                      </span>
                  </strong>
              </span>

                <small>
                    @if($recommend_book->tongren_yuanzhu_tagname)
                    <a class="btn btn-xs btn-success tag-button-left tag-blue" href="{{ route('books.booktag', $recommend_book->tongren_yuanzhu_tag_id) }}">{{$recommend_book->tongren_yuanzhu_tagname}}</a>
                    @endif
                    @if($recommend_book->tongren_cp_tagname)
                    <a class="btn btn-xs btn-warning tag-button-right tag-yellow" href="{{ route('books.booktag', $recommend_book->tongren_cp_tag_id) }}">{{$recommend_book->tongren_cp_tagname}}</a>
                    @endif

                    @if( $recommend_book->bianyuan == 1)
                    <span class="badge bianyuan-tag badge-tag">限</span>
                    @endif

                    @if(($recommend_book->last_chapter_title)&&($recommend_book->last_chapter_post_id == $recommend_book->last_post_id)&&($recommend_book->lastaddedchapter_at > Carbon\Carbon::now()->subHours(12)->toDateTimeString()))
                    <span class="badge newchapter-badge badge-tag">新</span>
                    @endif

                    @if(!$recommend_book->public)
                    <span class="glyphicon glyphicon-eye-close"></span>
                    @endif
                    @if($recommend_book->locked)
                    <span class="glyphicon glyphicon-lock"></span>
                    @endif
                    @if($recommend_book->noreply)
                    <span class="glyphicon glyphicon-warning-sign"></span>
                    @endif
                </small>
            </span>

            <span class = "pull-right">
                @if($recommend_book->anonymous)
                <span>{{ $recommend_book->majia ?? '匿名咸鱼'}}</span>
                @if((Auth::check()&&(Auth::user()->admin)))
                <span class="admin-anonymous"><a href="{{ route('user.show', $recommend_book->user_id) }}">{{ $recommend_book->name }}</a></span>
                @endif
                @else
                <a href="{{ route('user.show', $recommend_book->user_id) }}">{{ $recommend_book->name }}</a>
                @endif
            </span>
        </div>

        <div class="col-xs-12 h5 ">
            <!-- 帖子的推荐语、点击率（通过推荐版块点击）和回帖数 -->
            <span>{{ $recommend_book->recommendation }}</span>
            <span class = "pull-right smaller-10"><em><span class="glyphicon glyphicon-pencil"></span>{{ $recommend_book->total_char }}/<span class="glyphicon glyphicon-eye-open"></span>{{ $recommend_book->clicks }}/<span class="glyphicon glyphicon glyphicon-comment"></span>{{ $recommend_book->responded }}</em></span>
        </div>

        <div class="col-xs-12 h5 brief">
            <span class="grayout smaller-10"><a class="btn btn-xs btn-success sosad-button" href="{{ route('recommend_books.edit', $recommend_book->id)}}">修改推荐语</a></span>
            <span class="pull-right smaller-10"><em>
                <a href="{{ route('books.index',['channel'=>(int)($recommend_book->channel_id)]) }}">{{ config('constants.book_info')['channel_info'][$recommend_book->channel_id] }}</a>-<a href="{{ route('books.index',['book_length'=>$recommend_book->book_length]) }}">{{ config('constants.book_info')['book_lenth_info'][$recommend_book->book_length] }}</a>-<a href="{{ route('books.index',['book_status'=>$recommend_book->book_status]) }}">{{ config('constants.book_info')['book_status_info'][$recommend_book->book_status] }}</a>-<a href="{{ route('books.index',['label'=>$recommend_book->label_id]) }}">{{ $recommend_book->labelname }}</a>-<a href="{{ route('books.index',['sexual_orientation'=>$recommend_book->sexual_orientation]) }}">{{ config('constants.book_info')['sexual_orientation_info'][$recommend_book->sexual_orientation] }}</a>
            </em></span>
        </div>
    </div>
    <hr>
</article>
@endforeach
