@extends('layouts.default')
@section('title', $thread->title.'-'.$chapter->title)
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        @include('shared.errors')
        <div class="">
            <a type="btn btn-primary" href="{{ route('home') }}"><span class="glyphicon glyphicon-home"></span><span>首页</span></a>/<a href="{{ route('channel.show', ['channel'=>$thread->channel_id,'label'=>$thread->label_id]) }}">{{ $thread->label->labelname }}</a>/<a href="{{ route('book.show', $book) }}">{{ $thread->title }}</a>/<a href="{{route('book.showchapter', $chapter->id)}}">{{ $chapter->title }}</a>
        </div>
        {{ $posts->links() }}
        @if($posts->currentPage()==1)
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="text-center h5">
                    <div class="h4">
                        <a href="{{route('book.showchapter', $chapter->id)}}" class="bigger-20">{{ $chapter->title }}</a>
                    </div>
                    <?php $post = $chapter->mainpost ?>
                    <div class="grayout">
                        {{ $chapter->mainpost->title }}
                    </div>
                    <div class = "text-center">
                        @if ($thread->anonymous)
                        <p>{{ $thread->majia ?? '匿名咸鱼'}}</p>
                        @if((Auth::check()&&(Auth::user()->admin)))
                        <p class="admin-anonymous"><a href="{{ route('user.show', $thread->user_id) }}">{{ $thread->creator->name }}</a></p>
                        @endif
                        @else
                        <a href="{{ route('user.show',$thread->creator->id) }}">{{ $thread->creator->name }}</a>
                        @endif
                    </div>
                    <div class="grayout smaller-10 text-center">
                        发表于 {{ Carbon\Carbon::parse($chapter->mainpost->created_at)->diffForHumans() }}
                        @if($chapter->mainpost->created_at < $chapter->mainpost->edited_at )
                        修改于 {{ Carbon\Carbon::parse($chapter->mainpost->edited_at)->diffForHumans() }}
                        @endif
                    </div>
                </div>
            </div>
            <div class="panel-body">
                @if((Auth::check())&&(Auth::user()->admin))
                @include('admin._delete_post')
                @endif
                @if((($chapter->mainpost->bianyuan)||($thread->bianyuan))&&(!Auth::check()))
                <div class="text-center">
                    <h6 class="display-4"><a href="{{ route('login') }}">本章节为隐藏格式，只对注册用户开放，请登录后查看</a></h6>
                </div>
                @else
                <div class="text-left main-text {{ $chapter->mainpost->indentation? 'indentation':'' }}">
                    @if($chapter->mainpost->markdown)
                    {!! Helper::sosadMarkdown($chapter->mainpost->body) !!}
                    @else
                    {!! Helper::wrapParagraphs($chapter->mainpost->body) !!}
                    @endif
                    <br>
                </div>
                <div class="text-left grayout">
                    {!! Helper::wrapParagraphs($chapter->annotation) !!}
                </div>
                @endif
                <div class="">
                    <a class="smaller-10" href="{{ route('thread.showpost', $chapter->post_id) }}"><u>论坛讨论模式</u></a>
                    <span class="pull-right smaller-20"><em><span class="glyphicon glyphicon-pencil"></span><span>{{ $chapter->characters }}/</span><span class="glyphicon glyphicon-eye-open"></span>{{ $chapter->viewed }}/<span class="glyphicon glyphicon glyphicon-comment"></span>{{ $chapter->responded }}</em></span>
                </div>
                <div class="chapters">
                    <div class="row">
                        <div class="col-xs-6 text-center">
                            @if(!$previous)
                            <a href="#" class = "sosad-button btn btn-success btn-block disabled">这是第一章</a>
                            @else
                            <a href="{{ route('book.showchapter', $previous->id) }}" class = "sosad-button btn btn-success btn-block">上一章</a>
                            @endif
                        </div>
                        <div class="col-xs-6 text-center">
                            @if(!$next)
                            <a href="#" class = "sosad-button btn btn-success btn-block disabled">这是最后一章</a>
                            @else
                            <a href="{{ route('book.showchapter', $next->id) }}" class = "sosad-button btn btn-success btn-block" >下一章</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @if (Auth::check())
            <?php $post = $chapter->mainpost; ?>
            <div class="post-vote text-right">
                @include('posts._post_vote')
            </div>
            @else
            <h6 class="display-4">请 <a href="{{ route('login') }}">登录</a> 后参与讨论</h6>
            @endif
            @if ($chapter->mainpost->comments->count() > 0)
            <div class="panel-footer">
                <?php $post = $chapter->mainpost; $postcomments= $post->comments;?>
                @include('posts._post_comments')
            </div>
            @endif
            @endif
        </div>
        @foreach($posts as $key=>$post)
        @if(!$post->maintext)
        @include('posts._post')
        @endif
        @endforeach
        {{ $posts->links() }}
        @if(Auth::check())
        <?php $defaultchapter = $chapter->id ?>
        @include('threads._reply')
        @endif
    </div>
</div>
@stop
