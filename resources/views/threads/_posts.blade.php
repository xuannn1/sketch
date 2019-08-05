<!-- 展示该主题下每一个帖子 -->
@foreach($posts as $key=>$post)
@include('posts._post')
@endforeach
