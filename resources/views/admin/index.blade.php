@extends('layouts.default')
@section('title', '管理界面')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading"><h4>待办事</h4></div>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">
                <h4>标签（tag）管理</h4>
                <ul>
                    <li><a href="{{ route('admin.createtag') }}">新建标签</a></li>
                </ul>
                <h4>用户（user）管理</h4>
                <ul>
                    <li><a href="{{route('admin.searchusersform')}}">搜索用户</a></li>
                    <li><a href="{{route('admin.sendpublicnoticeform')}}">发送公共通知</a></li>
                </ul>

                <h4>题头（Quote）管理</h4>
                <ul>
                    <li><a href="{{ route('quote.review_index', ['withReviewState'=>'notYetReviewed']) }}">文案摘句审核</a></li>
                </ul>

                <h4>作业（Homework）管理</h4>
                <ul>
                    <li><a href="#">新建作业</a></li>
                    <li><a href="#">作业列表</a></li>
                </ul>

                <h4>邀请码（Invitation Token）管理</h4>
                <ul>
                    <li><a href="{{ route('invitation_tokens.index') }}">查看邀请码列表</a></li>
                </ul>

                <h4>答题测试（Quiz）管理</h4>
                <ul>
                    <li><a href="{{ route('quiz.review') }}">查看题库列表</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

@stop
