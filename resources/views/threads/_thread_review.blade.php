<div class="panel panel-default" id = "post{{ $review->post->id }}">
    <div class="panel-heading">
        <div class="row">
            <div class="col-xs-12 h5">
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
                <a href="{{ route('thread.showpost',$review->post_id) }}" class="pull-right"><em>查看详情</em></a>
            </div>
        </div>
    </div>
    <div class="panel-body post-body">
        <div class="main-text">
            {{ $review->post->brief }}
        </div>
    </div>
    @if(Auth::check())
    <div class="text-right post-vote">
        @if(Auth::user()->level >= 1)
        <span class="voteposts"><button class="btn btn-default btn-xs" data-id="{{$review->post->id}}"  id = "{{ $review->post_id.'upvote'}}" onclick="vote_post({{$review->post_id}},'upvote')" ><span class="glyphicon glyphicon-heart">{{ $review->post->upvote_count }}</span></button></span>
        @endif
    </div>
    @endif
</div>
