<div class="text-right post-vote h5">
    <span class="voteposts"><button class="btn btn-default btn-md" data-id="{{$post->id}}" onclick="voteItem('post', {{$post->id}}, 'upvote')" ><span class="glyphicon glyphicon-heart"></span><span id="post{{$post->id}}upvote">{{ $post->upvote_count }}</span></button></span>
    
    <span><a href="#" data-id="{{$post->id}}" data-toggle="modal" data-target="#TriggerPostReward{{ $post->id }}" class="btn btn-default btn-md">打赏</a></span>

    @if((!$thread->is_locked&&!$thread->no_reply&&!Auth::user()->no_posting&&$post->fold_state===0&&Auth::user()->level >= 2)||(Auth::user()->isAdmin()))
        <span ><a href = "#replyToThread" class="btn btn-default btn-md" onclick="replytopost({{ $post->id }}, '{{ StringProcess::trimtext($post->title.$post->brief, 50)}}');show_is_comment();"><span class="glyphicon glyphicon-comment">{{ $post->reply_count }}</span></a></span>
    @endif

    @if(($post->user_id===Auth::id())&&(Auth::user()->isAdmin()||((!$thread->is_locked)&&(!$thread->no_reply)&&($post->fold_state===0)&&($thread->channel()->allow_edit)&&!Auth::user()->no_posting)))
        <span><a class="btn btn-danger sosad-button btn-md" href="{{ route('post.edit', $post->id) }}">编辑</a></span>
    @endif
    @if($thread->user_id===Auth::id()&&!Auth::user()->no_posting)
    <a href="#" data-id="{{$post->id}}" data-toggle="modal" data-target="#TriggerAdvancedManaging{{ $post->id }}" class="btn btn-default btn-md"><i class="fa fa-cog bigger-20" aria-hidden="true"></i></a>
    @include('posts._management_form')
    @endif
</div>
@include('posts._reward_form')
