@extends('layouts.default')
@section('title', '发文发帖')

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="">
                <h1>总发文发帖入口</h1>
                <div class="">
                    <div class="font-4">
                        你的用户等级是{{$user->level}}级
                    </div>
                    <div class="grayout font-6">
                        用户等级1级以上才可发文，4级以上才可发讨论帖。
                    </div>
                    <div class="font-4">
                        <a href="{{ route('quiz.quiz_entry') }}">你最高答过{{$user->quiz_level}}级题&nbsp;&nbsp; >>前去答题</a>
                    </div>
                    <div class="grayout font-6">
                        至少答过1级题才能发书籍，至少答过2级题才能发主题讨论
                    </div>
                    <div class="font-4">
                        <span>发文发帖前请务必阅读：<a href="http://sosad.fun/threads/136">《版规》</a>、<a href="{{ route('help') }}">《帮助》</a></span>，文章、帖子的删除等操作在<a href="https://sosad.fun/threads/88">《申删转区楼》</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="">
                    <div class="text-center font-4">
                        发布文章
                    </div>
                    <hr>
                    <a href="{{ route('books.create') }}" class="btn btn-md btn-info btn-block sosad-button">
                        原创小说
                    </a>
                    <a href="{{ route('books.create') }}" class="btn btn-md btn-info btn-block sosad-button">
                        同人小说
                    </a>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-body">
                <div class="">
                    <div class="text-center font-4">
                        发布水区讨论帖
                    </div>
                    <hr>
                    <a href="{{ route('threads.create',['channel_id'=>4]) }}" class="btn btn-md btn-info btn-block sosad-button">
                        读写交流
                    </a>
                    <a href="{{ route('threads.create',['channel_id'=>5]) }}" class="btn btn-md btn-info btn-block sosad-button">
                        日常闲聊（安利、吐槽）
                    </a>
                    <a href="{{ route('threads.create',['channel_id'=>6]) }}" class="btn btn-md btn-info btn-block sosad-button">
                        随笔（诗歌、散文、翻译）
                    </a>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-body">
                <div class="">
                    <div class="text-center font-4">
                        发布个人相关帖
                    </div>
                    <hr>
                    <a href="{{ route('threads.create',['channel_id'=>13]) }}" class="btn btn-md btn-info btn-block sosad-button">
                        创建清单（读书、电影记录等）
                    </a>
                    <a href="{{ route('threads.create',['channel_id'=>14]) }}" class="btn btn-md btn-info btn-block sosad-button">
                        创建问题箱
                    </a>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-body">
                <div class="">
                    <div class="text-center font-4">
                        发布版务相关帖
                    </div>
                    <hr>
                    <a href="{{ route('threads.create',['channel_id'=>7]) }}" class="btn btn-md btn-info btn-block sosad-button">
                        站务管理（本版不可修改）
                    </a>
                    <a href="{{ route('threads.create',['channel_id'=>8]) }}" class="btn btn-md btn-info btn-block sosad-button">
                        违规举报（本版不可修改）
                    </a>
                    <a href="{{ route('threads.create',['channel_id'=>9]) }}" class="btn btn-md btn-info btn-block sosad-button">
                        投诉仲裁（本版不可匿名，不可修改）
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@stop
