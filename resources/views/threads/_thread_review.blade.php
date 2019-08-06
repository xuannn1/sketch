<div class="panel panel-default" id = "post{{ $review->post->id }}">
    <div class="panel-body post-body">
        <div class="h5">
            @if($review->editor_recommend)
            <span class="recommend-label">
                <span class="glyphicon glyphicon-grain recommend-icon"></span>
                <span class="recommend-text">推</span>
            </span>
            @endif
            <span>
                @if($review->post->author)
                @if($review->post->is_anonymous)
                <span>{{ $review->post->majia ?? '匿名咸鱼'}}</span>
                @else
                <a href="{{ route('user.show', $review->post->user_id) }}">{{ $review->post->author->name }}</a>
                @endif
                @endif
            </span>
        </div>
        <div class="main-text">
            <a href="{{ route('post.show', $review->post_id) }}" class="font-weight-400">{{ $review->post->brief }}</a>
            @if(Auth::check()&&Auth::user()->level >= 1)
                <span class="voteposts pull-right"><button class="btn btn-default btn-xs" data-id="{{$review->post->id}}" onclick="voteItem('post', {{$review->post->id}}, 'upvote')" ><span class="glyphicon glyphicon-heart"></span><span id="post{{$review->post->id}}upvote">{{ $review->post->upvote_count }}</span></button></span>
            @endif
        </div>
    </div>


</div>
