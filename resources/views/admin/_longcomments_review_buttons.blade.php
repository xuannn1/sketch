<div class="longcommentbutton{{$post->id}}">
    <code><span class="longcommentreviewstatus{{ $post->id }}">{{$post->approved? '对外显示':'不显示'}}</span></code>
    &nbsp;
    <button class="btn-small sosad-button-tag approvebutton{{$post->id}} {{$post->reviewed? 'hidden':''}}" type="button" name="button" onClick="toggle_review_longcomment({{$post->id}},'approve')">对外显示</button>
    <button class="btn-small sosad-button-tag disapprovebutton{{$post->id}} {{$post->reviewed? 'hidden':''}}" type="button" name="button" onClick="toggle_review_longcomment({{$post->id}},'disapprove')">不对外显示</button>
    <button class="btn-small sosad-button-tag togglebutton{{$post->id}} {{$post->reviewed? '':'hidden'}}"  type="button" name="button" onClick="toggle_re_review_buttons({{$post->id}},{{$post->approved}})">重新审核</button>
</div>
