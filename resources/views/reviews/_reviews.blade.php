<div class="panel panel-default">
    <div class="panel-heading">
        <h3>评论目录</h3>
    </div>
    @foreach($thread->reviews as $post)
    <div class="panel-body">
        <div class="row">
            <div class="col-xs-12">
                <span>
                    @if($post->title)
                    <a href="{{ route('post.show', $post->id) }}">{{ $post->title }}</a>&nbsp;
                    @endif
                    @if($post->review->editor_recommend)
                    <span class="recommend-label smaller-20">
                        <span class="glyphicon glyphicon-grain recommend-icon"></span>
                        <span class="recommend-text">推</span>
                    </span>
                    @endif
                    @if($post->review->reviewee)
                    <a class="grayout" href="{{ route('thread.show_profile', $post->review->thread_id) }}">《{{ $post->review->reviewee->title }}》</a>
                    @endif
                    <!-- 星级评价 -->
                    <span>
                        @for ($i = 0; $i < $post->review->rating; $i++)
                        @if($i%2!=0)
                        <i class="fa fa-star recommend-star" aria-hidden="true"></i>
                        @endif
                        @endfor

                        @if($post->review->rating>0&&$post->review->rating%2!=0)
                        <i class="fa fa-star-half-o recommend-star" aria-hidden="true"></i>
                        @endif
                    </span>

                    @if($post->review->recommend)
                    <span class="badge newchapter-badge badge-tag"><i class="fa fa-heartbeat" aria-hidden="true"></i>推荐</span>
                    @endif
                    <span class="grayout pull-right smaller-20">
                        {{ $post->created_at->diffForHumans() }}
                        @if($post->created_at<$post->edited_at)
                        /{{ $post->edited_at->diffForHumans() }}
                        @endif
                    </span>
                </span>
            </div>
            <div class="col-xs-12 post-body smaller-10">
                <a href="{{ route('post.show', $post->id) }}" class="font-weight-400">
                    {{StringProcess::trimtext($post->body,120)}}
                </a>
                <span class="pull-right">
                    <span class="voteposts"><span class="btn btn-default btn-xs"><span class="glyphicon glyphicon-heart">{{ $post->upvote_count }}</span></span></span>
                </span>
            </div>
        </div>
    </div>
    <hr>
    @endforeach
</div>
