@foreach($postcomments as $comment_no=>$postcomment)
@if ($comment_no < 3)
@include('posts._post_comment')
@elseif ($comment_no == 3)
<a href="{{ route('post.show', $post->id) }}">查看全部点评</a>
@endif
@endforeach
