<div>
    <span class="smaller-20 grayout">
        {{ $post->created_at? $post->created_at->diffForHumans():'' }}
    </span>&nbsp;
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
    </span>
</div>
<div class="">
    <a href="{{ route('post.show', $post->id) }}">{{ StringProcess::trimtext($post->body, 50) }}</a>
</div>
