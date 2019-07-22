<div class="modal fade" id="TriggerAdvancedManaging{{ $post->id }}" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="form-group text-left">
                <div class="">
                    <h1>管理回帖：</h1>
                    <h2>{{$post->title}}</h2>
                    <h3>{{$post->brief}}</h3>
                </div>
                <div class="">
                    @if($post->type==='review'||$post->type==='question'||$post->type==='answer'||$post->type==='chapter')
                    <a href="{{ route('post.turn_to_post', $post->id) }}" class="btn btn-lg sosad-button-control">转化成普通回帖</a>&nbsp;
                    @endif
                    @if($post->user_id==Auth::id()&&$post->type==='post'&&$thread->channel()->type==='list')
                    <a href="{{ route('post.turn_to_review', $post->id) }}" class="btn btn-lg sosad-button-control">把普通回帖转化成书评</a>&nbsp;
                    @endif
                    @if($post->user_id==Auth::id()&&$post->type==='post'&&$thread->channel()->type==='book')
                    <a href="{{route('post.turn_to_chapter', $post->id)}}" class="btn btn-lg sosad-button-control">把普通回帖转化成章节</a>&nbsp;
                    @endif
                    @if($post->user_id!=Auth::id()&&$thread->channel()->type==='book')
                    <a href="#" class="btn btn-lg sosad-button-control">折叠回帖（待做）</a>&nbsp;
                    @endif
                    @if($post->user_id===Auth::id()&&($post->reply_to_id>0)&&$thread->channel()->type==='box')
                    <a href="#" class="btn btn-lg sosad-button-control">把回复转化成回答（待做）</a>&nbsp;
                    @endif
                    @if($post->user_id!=Auth::id()&&$thread->channel()->type==='box')
                    <a href="{{route('post.box_owner_delete_post', $post->id)}}" class="btn btn-lg sosad-button-control">删除问题楼里不合适问题</a>
                    @endif
                </div>

            </div>

        </div>
    </div>
</div>
