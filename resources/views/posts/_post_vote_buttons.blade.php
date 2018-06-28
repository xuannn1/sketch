<button class="btn btn-xs btn-info sosad-button" data-id="{{$post->id}}"  id = "{{$post->id.'upvote'}}" onclick="vote_post({{$post->id}},'upvote')" ><span class="glyphicon glyphicon-heart">{{ $post->up_voted }}</button>
<!-- <button class="glyphicon glyphicon-thumbs-down vote" data-id="{{$post->id}}"  id = "{{$post->id.'downvote'}}" onclick="vote_post({{$post->id}},'downvote')">{{ $post->down_voted }}</button>
<button class="glyphicon funny_post vote vote-text" data-id="{{$post->id}}"  id = "{{$post->id.'funny'}}" onclick="vote_post({{$post->id}},'funny')">搞笑{{ $post->funny }}</button>
<button class="glyphicon fold_post vote vote-text" data-id="{{$post->id}}"  id = "{{$post->id.'fold'}}" onclick="vote_post({{$post->id}},'fold')">折叠{{ $post->fold }}</button> -->
