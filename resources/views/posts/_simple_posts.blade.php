<!-- 简单展示一串帖子 -->
@foreach($posts as $key=>$post)
@include('posts._simple_post')
@endforeach
