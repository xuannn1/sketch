@extends('layouts.default')
@section('title', '答题中心')

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-body text-center">
                <div class="container-fluid">
                    <h1>废文使用测试题</h1>
                    <h4>{{ $user->name }} 您好！欢迎您参与废文使用测试！在这里您将彻底学习如何做条好鱼。每位咸鱼初次答对全部题目时，还会获得<code>升级</code>必备的分值<code>奖励</code>，还等什么呢，快来尝试一下吧！</h4>
                    <h3>您当前的答题等级是：{{$user->quiz_level}}级</h3>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="">
                    <a href="{{route('quiz.taketest',['level'=>0])}}" class="btn btn-lg btn-info btn-block sosad-button">
                        1级题（{{$user->quiz_level>=1?'已完成':'未完成'}}）
                    </a>
                </div>
                <hr>
                <div class="">
                    <a href="{{route('quiz.taketest',['level'=>1])}}" class="btn btn-lg btn-info btn-block sosad-button">
                        2级题（{{$user->quiz_level>=2?'已完成':'未完成'}}）
                    </a>
                </div>
                <hr>
                <div class="">
                    <a href="{{route('quiz.taketest',['level'=>2])}}" class="btn btn-lg btn-info btn-block sosad-button">
                        3级题（{{$user->quiz_level>=3?'已完成':'未完成'}}）
                    </a>
                </div>
                <div class="">
                    <h5>(更多题目还在路上哦！)</h5>
                </div>
            </div>
        </div>


    </div>
</div>
@stop
