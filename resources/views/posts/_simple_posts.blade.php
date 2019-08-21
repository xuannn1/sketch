<!-- 简单展示一串帖子 -->
@foreach($posts as $key=>$post)
<?php $show_author=false; ?>
@include('posts._simple_post')
@endforeach
