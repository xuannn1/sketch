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
         <a href="{{ route('books.index', ['channel'=>$thread->channel_id]) }}">{{ $thread->channel->channelname }}</a>
         &nbsp;/&nbsp;
         <a href="{{ route('books.index', ['label'=>$thread->label_id]) }}">{{ $thread->label->labelname }}</a>
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
                  <a href=" {{ route('download.book_noreview_text', $thread->id) }} ">下载txt书籍</a>
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
          <!-- 对于短篇一发完结的情况，直接显示正文 -->
          @if((count($book->chapters)==1)&&($book->book_status == 2)&&($book->book_length ==1))
          <?php $post = $book->chapters[0]->mainpost; ?>
              <div class="panel panel-default">
                  <div class="panel-body post-body">
                     @include('posts._post_body')
                  </div>
              </div>
          @else
              <div class="panel panel-body">
                 @include('books._chapters')
              </div>
          @endif
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
