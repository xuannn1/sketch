@foreach($short_reviews as $post)
@if($post->review&&$post->review->reviewee)
<article class="{{ 'recommend_book'.$post->review->thread_id }}">
    <div class="row">
        <div class="col-xs-12 h5">
            <!-- 帖子的相关信息 -->
            <span>
              <span class="bigger-20">
                  <strong>
                      <span>
                          <a href="{{ route('thread.show_profile', $post->review->thread_id) }}">
                              《{{ $post->review->reviewee->title }}》
                          </a>
                      </span>
                  </strong>
              </span>

                <small>
                    @if( $post->review->reviewee->is_bianyuan == 1)
                    <span class="badge bianyuan-tag badge-tag">限</span>
                    @endif
                    @if(!$post->review->reviewee->is_public)
                    <span class="glyphicon glyphicon-eye-close"></span>
                    @endif
                    @if($post->review->reviewee->is_locked)
                    <span class="glyphicon glyphicon-lock"></span>
                    @endif
                    @if($post->review->reviewee->no_reply)
                    <span class="glyphicon glyphicon-warning-sign"></span>
                    @endif
                </small>
            </span>

            <span class = "pull-right">
                @if($post->review->reviewee->author)
                    @if($post->review->reviewee->is_anonymous)
                    <span>{{ $post->review->reviewee->majia ?? '匿名咸鱼'}}</span>
                        @if((Auth::check()&&(Auth::user()->isAdmin())))
                        <span class="admin-anonymous"><a href="{{ route('user.show', $post->user_id) }}">{{ $post->review->reviewee->author->name }}</a></span>
                        @endif
                    @else
                        <a href="{{ route('user.show', $post->review->reviewee->user_id) }}">{{  $post->review->reviewee->author->name }}</a>
                    @endif
                @endif
            </span>
        </div>

        <div class="col-xs-12 h5 ">
            <!-- 帖子的推荐语、点击率（通过推荐版块点击） -->
            <a href="{{route('post.show', $post->id)}}" class="font-weight-400">推荐语：{{ StringProcess::trimtext($post->body,120) }}</a>
        </div>
    </div>
    <hr>
</article>
@endif
@endforeach
