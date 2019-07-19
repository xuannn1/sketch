@extends('layouts.default')
@section('title', '发文发帖')

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="">
                <h1>总发文发帖入口</h1>
                <div class="">
                    <h4>您的用户等级是{{$user->level}}级</h4>
                    <span class="grayout">
                        用户等级1级以上才可发文，4级以上才可发讨论帖。
                    </span>
                    <h4>您最高答过{{$user->quiz_level}}级题</h4>
                    <span class="grayout">
                        至少答过1级题才能发文。
                    </span>
                    <h5><span style="color:#d66666">发文发帖前请务必阅读：<a href="http://sosad.fun/threads/136">《<u>版规的详细说明</u>》</a><br>关于网站使用的常规问题：<a href="{{ route('help') }}">《<u>使用帮助</u>》</a></span></h5>
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
                    <a href="{{ route('books.create') }}" class="btn btn-lg btn-info btn-block sosad-button">
                        原创小说
                    </a>
                    <a href="{{ route('books.create') }}" class="btn btn-lg btn-info btn-block sosad-button">
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
                    <a href="{{ route('threads.create',['channel_id'=>4]) }}" class="btn btn-lg btn-info btn-block sosad-button">
                        读写交流
                    </a>
                    <a href="{{ route('threads.create',['channel_id'=>5]) }}" class="btn btn-lg btn-info btn-block sosad-button">
                        日常闲聊（安利、吐槽）
                    </a>
                    <a href="{{ route('threads.create',['channel_id'=>6]) }}" class="btn btn-lg btn-info btn-block sosad-button">
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
                    <a href="{{ route('threads.create',['channel_id'=>13]) }}" class="btn btn-lg btn-info btn-block sosad-button">
                        创建清单（读书、电影记录等）
                    </a>
                    <a href="{{ route('threads.create',['channel_id'=>14]) }}" class="btn btn-lg btn-info btn-block sosad-button">
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
                    <a href="{{ route('threads.create',['channel_id'=>7]) }}" class="btn btn-lg btn-info btn-block sosad-button">
                        站务管理
                    </a>
                    <a href="{{ route('threads.create',['channel_id'=>8]) }}" class="btn btn-lg btn-info btn-block sosad-button">
                        违规举报（本版不可修改）
                    </a>
                    <a href="{{ route('threads.create',['channel_id'=>9]) }}" class="btn btn-lg btn-info btn-block sosad-button">
                        投诉仲裁（本版不可匿名，不可修改）
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@stop
