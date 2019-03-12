@foreach($recommend_books as $recommend_book)
<article class="{{ 'recommend_book'.$recommend_book->id }}">
    <div class="row">
        <div class="col-xs-12 h5">
            <!-- 帖子的相关信息 -->
            <span>
              <span class="bigger-20">
                  <strong>
                      <span>
                          <a href="{{ route('thread.show', $recommend_book->thread_id) }}">
                              {{ $recommend_book->title }}
                          </a>
                      </span>
                  </strong>
              </span>

                <small>
                    @if( $recommend_book->bianyuan == 1)
                    <span class="badge bianyuan-tag badge-tag">限</span>
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
            <!-- 帖子的推荐语、点击率（通过推荐版块点击） -->
            <span>推荐语：{{ $recommend_book->recommendation }}</span>
        </div>
    </div>
    <hr>
</article>
@endforeach
