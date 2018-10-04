@foreach($upvotes as $post)
<article class="margin5">
    <div class="row">
        <div class="col-xs-12">
            <span id="simpleupvoted{{$post->id}}" class="grayout">
                <a href="{{ route('user.show', $post->upvoter_id) }}">{{ $post->upvoter_name }}</a>&nbsp;{{ Carbon\Carbon::parse($post->upvoted_at)->diffForHumans() }}赞赏了您的帖子
                <a href="{{ route('thread.showpost', $post->id) }}" class="grayout">
                    {!! Helper::trimtext($post->body,5) !!}
                </a></span>
        </div>
        <div class="main-text col-xs-12">
            <span id="fullupvoted{{$post->id}}">
                {!! Helper::trimtext($post->body,60) !!}
            </span>
        </div>
    </div>
</article>
@endforeach
