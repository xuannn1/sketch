@foreach($posts as $post)

<div class="panel panel-default {{ $post->is_folded ? 'collapse':'' }} " id = "post{{ $post->id }}">
    <div class="panel-heading">
        <span>

            @if($post->review->reviewee)
            <a href="{{ route('thread.show_profile', $post->review->thread_id) }}">《{{ $post->review->reviewee->title }}》</a>
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
                修改于&nbsp;{{ $post->edited_at->diffForHumans() }}
                @endif
            </span>
        </span>
    </div>
    <div class="panel-body post-body">
        <div class="">
            <a href="{{ route('post.show', $post->id) }}">{{ StringProcess::trimtext($post->body, 50) }}</a>
        </div>
    </div>
    <div class="text-right post-vote">
        <span class="voteposts"><span class="btn btn-default btn-xs"><span class="glyphicon glyphicon-heart">{{ $post->upvote_count }}</span></span></span>

        <span class="btn btn-default btn-xs"><span class="glyphicon glyphicon-comment">{{ $post->reply_count }}</span></span>
    </div>
</div>
@endforeach
