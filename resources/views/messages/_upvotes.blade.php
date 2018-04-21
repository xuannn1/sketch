@foreach($upvotes as $post)
<article class="">
    <hr>
    <div class="row">
        <div class="col-xs-12">
            <span id="simpleupvoted{{$post->id}}"><em>
                <a href="{{ route('user.show', $post->upvoter_id) }}">{{ $post->upvoter_name }}</a>&nbsp;{{ Carbon\Carbon::parse($post->upvoted_at)->diffForHumans() }}点赞了您的帖子
                <a href="{{ route('thread.showpost', $post->id) }}">
                    {!! Helper::trimtext($post->body,10) !!}
                </a></em></span>
            </div>
            <div class="col-xs-12">
                <span id="fullupvoted{{$post->id}}">
                    {!! Helper::trimtext($post->body,60) !!}
                </span>
            </div>
        </div>
    </article>
    @endforeach
