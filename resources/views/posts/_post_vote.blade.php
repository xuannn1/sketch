<span class="voteposts">
   @include('posts._post_vote_buttons')
</span>
@if((!$thread->locked)&&(Auth::user()->no_posting < Carbon\Carbon::now()))
<span class="voteposts">
  <a href="#" data-id="{{$post->id}}" data-toggle="modal" data-target="#TriggerPostComment{{ $post->id }}" class="btn-xs">点评</a>
</span>
<span ><a href = "#replyToThread" class="btn-xs" onclick="replytopost({{ $post->id }}, '{{ Helper::trimtext($post->body, 10)}}')">回复</a></span>
@endif

@if($post->user_id == Auth::id()&&(!$thread->locked))
   @if($post->maintext)
      <span><a class="btn-xs" href="{{ route('book.editchapter', $post->chapter_id) }}">编辑</a></span>
   @else
      <span><a class="btn-xs" href="{{ route('post.edit', $post->id) }}">编辑</a></span>
   @endif
@endif

<div class="modal fade" id="TriggerPostComment{{ $post->id }}" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
         <form action="{{ route('postcomment.store',$post->id)}}" method="POST">
            {{ csrf_field() }}
            <div class="form-group">
               <textarea name="body" rows="2" id="postcomment{{$post->id}}" class="form-control" placeholder="点评："></textarea>
               <button type="button" onclick="retrievecache('postcomment{{$post->id}}')" class="sosad-button-control addon-button">恢复数据</button>
               <div class="checkbox text-left">
                 <label><input type="checkbox" name="anonymous" onclick="document.getElementById('majiacommentpost{{$post->id}}').style.display = 'block'">马甲？</label>
                 <div class="form-group text-right" id="majiacommentpost{{$post->id}}" style="display:none">
                     <input type="text" name="majia" class="form-control" value="{{Auth::user()->majia ?:'匿名咸鱼'}}" placeholder="请输入不超过10字的马甲">
                     <label for="majia"><small>(马甲仅勾选“匿名”时有效)</small></label>
                 </div>
               </div>
            </div>

            <div class="">
               <button type="submit" class="btn btn-primary sosad-button btn-sm">点评</button>
            </div>
         </form>
      </div>
   </div>
</div>
