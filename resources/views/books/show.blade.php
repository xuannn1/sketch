@extends('layouts.default')
@section('title', $thread->title)

@section('content')
<div class="container-fluid">
   <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
      @include('shared.errors')
      <?php $defaultchapter = 0 ?>
      <!-- 首页／版块／类型 -->
      <div class="">
         <a type="btn btn-primary" href="{{ route('home') }}"><span class="glyphicon glyphicon-home"></span><span>首页</span></a>
         &nbsp;/&nbsp;
         <a href="{{ route('books.original', ($book->original+0)) }}">{{ $thread->channel->channelname }}</a>
         &nbsp;/&nbsp;
         <a href="{{ route('books.booklabel', $thread->label_id) }}">{{ $thread->label->labelname }}</a>
         &nbsp;/&nbsp;
         <a href="{{ route('book.show',$book->id) }}">{{ $thread->title }}</a>
      </div>
      {{ $posts->links() }}
      @if($posts->currentPage()==1)
      <div class="panel panel-default">
       <!-- 主题介绍部分 -->
         <div class="panel-body">
            @include('books._book_profile')
            <div><a href="{{ route('thread.show', $thread) }}">论坛讨论模式</a></div>
         </div>
         <!-- 对主题进行投票／收藏／点赞等操作 -->
         <div class="panel-vote">
          @if(Auth::check())
            <div class="text-right h6">
              <a href=" {{ route('book_download.txt', $thread->id) }} ">下载txt书籍（建设中）</a>
            </div>
            @include('threads._thread_vote')
            @else
            <h6 class="display-4">请 <a href="{{ route('login') }}">登录</a> 后参与讨论</h6>
            @endif
         </div>
         <!-- 对本主题的点评 -->
         @if ($thread->mainpost->comments->count()>0)
         <div class="panel-footer">
            <?php $post = $thread->mainpost; $postcomments= $post->comments;?>
            @include('posts._post_comments')
         </div>
         @endif
      </div>
      <div class="panel panel-body">
         @include('books._chapters')
      </div>
      @endif
      @foreach($posts as $key=>$post)
         @include('posts._post')
      @endforeach
      {{ $posts->links() }}
      @if(Auth::check())
         @include('threads._reply')
      @endif
   </div>
</div>
@stop
