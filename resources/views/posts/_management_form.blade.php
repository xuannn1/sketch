<div class="modal fade" id="TriggerAdvancedManaging{{ $post->id }}" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="form-group text-left">
                <div class="">
                    <h1>管理回帖：</h1>
                    <h2>{{$post->title}}</h2>
                    <h3>{{$post->brief}}</h3>
                </div>
                @if($post->type==='review'||$post->type==='question'||$post->type==='answer'||$post->type==='chapter')
                <div class="">
                    <a href="{{ route('post.turn_to_post', $post->id) }}" class="btn btn-md btn-block sosad-button-control">转化成普通回帖</a>
                </div>
                @endif
                @if($post->user_id==Auth::id()&&$post->type==='post'&&$thread->channel()->type==='list')
                <div class="">
                    <a href="{{ route('post.turn_to_review', $post->id) }}" class="btn btn-md btn-block sosad-button-control">转化成书评</a>
                </div>
                @endif
                @if($post->user_id==Auth::id()&&$post->type==='post'&&$thread->channel()->type==='book')
                <div class="">
                    <a href="#" class="btn btn-md btn-block sosad-button-control">转化成章节（待做）</a>
                </div>
                @endif
                @if($post->user_id!=Auth::id()&&$thread->channel()->type==='book')
                <div class="">
                    <a href="#" class="btn btn-md btn-block sosad-button-control">折叠回帖（待做）</a>
                </div>
                @endif
                @if($post->user_id==Auth::id()&&$post->type==='comment'&&$thread->channel()->type==='box')
                <div class="">
                    <a href="#" class="btn btn-md btn-block sosad-button-control">转化成回答（待做）</a>
                </div>
                @endif
                @if($post->user_id!=Auth::id()&&$post->type==='post'&&$thread->channel()->type==='box')
                <div class="">
                    <a href="#" class="btn btn-md btn-block sosad-button-control">转化成问题（待做）</a>
                </div>
                <div class="">
                    <a href="#" class="btn btn-md btn-block sosad-button-control">删除它（待做）</a>
                </div>
                @endif
            </div>

        </div>
    </div>
</div>
