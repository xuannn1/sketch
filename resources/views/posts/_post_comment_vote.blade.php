<!-- <span class="dropdown">
  <button class="btn btn-default btn-xs dropdown-toggle" type="button"  data-toggle="dropdown" id="votecomment{{ $comment_no }}" aria-haspopup="true" aria-expanded="false">回应<span class="caret"></span>
   </button>
   <ul class="dropdown-menu" aria-labelledby="votecomment{{ $comment_no }}">
      <li>
         <div class="text-center">
            <span><a href="#">搞笑{{ $postcomment->funny }}</a></span>&nbsp;
            <span><a href="#">折叠{{ $postcomment->fold }}</a></span>&nbsp;
            <span><a href="#">我要回复</a></span>
         </div>
      </li>
    <li role="separator" class="divider"></li>
    <li><div class="text-center"><a href="#" class="glyphicon glyphicon-ban-circle">举报</a></div></li>
  </ul>
</span> -->
@if(Auth::id()==$postcomment->user_id)
<button type="button" class="btn btn-xs btn-danger sosad-button" onclick="deletepostcomment({{$postcomment->id}})">删除点评</button>
@endif
