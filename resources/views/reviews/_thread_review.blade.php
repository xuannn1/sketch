<div class="panel panel-default" id = "post{{ $review->post->id }}">
    <div class="panel-heading">
        <div class="row">
            <div class="col-xs-12 text-right">
                <span class="pull-left">
                    @if($review->editor_recommend)
                    <span class="recommend-label">
                        <span class="glyphicon glyphicon-grain recommend-icon"></span>
                        <span class="recommend-text">推</span>
                    </span>
                    @endif
                    <a href="{{route('post.show', $review->post_id)}}" >{{$review->post->title}}</a>
                </span>

                <!-- 显示作者名称 -->
                <span>
                    @if($review->post->author)
                    @if ($review->post->is_anonymous)
                    <span>{{ $review->post->majia ?? '匿名咸鱼'}}</span>
                    @else
                    <a href="{{ route('user.show', $review->post->user_id) }}">
                        {{ $review->post->author->name }}
                    </a>
                    @endif
                    @endif
                </span>
            </div>
        </div>
    </div>
    <div class="panel-body post-body">
        <div class="main-text text-left">
            <a href="{{route('post.show', $review->post_id)}}" ><span class="font-weight-400">{{ StringProcess::trimtext($review->post->body,120) }}</span></a>
            @if(Auth::check()&&Auth::user()->level >= 1)
                <span class="voteposts pull-right"><button class="btn btn-default btn-xs" data-id="{{$review->post->id}}" onclick="voteItem('post', {{$review->post->id}}, 'upvote')" ><span class="glyphicon glyphicon-heart"></span><span id="post{{$review->post->id}}upvote">{{ $review->post->upvote_count }}</span></button></span>
            @endif
        </div>
    </div>
</div>
