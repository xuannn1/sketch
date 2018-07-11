@extends('layouts.default')
@section('title', '管理界面')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading"><h4>待办事</h4></div>
            <div class="panel-body">
                <ul>
                    <li><a href="#">同人文章归类</a></li>
                    <li><a href="#">普通投诉（人身攻击、三次元信息）</a></li>
                    <li><a href="#">侵权投诉</a></li>
                    <li><a href="{{ route('quotes.review') }}">文案摘句审核</a></li>
                    <li><a href="{{ route('admin.review_longcomments') }}">长评审核</a></li>
                </ul>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">
                <h4>标签（tag）管理</h4>
                <ul>
                    <li><a href="{{ route('admin.createtag') }}">新建标签</a></li>
                </ul>
                <h4>类别（label）管理</h4>
                <ul>
                    <li><a href="#">新建类别</a></li>
                    <li><a href="#">类别列表</a></li>
                </ul>
                <h4>用户（user）管理</h4>
                <ul>
                    <li><a href="#">用户列表</a></li>
                    <li><a href="{{route('admin.sendpublicmessageform')}}">发送公共通知</a></li>
                </ul>
                <h4>主题（thread）管理</h4>
                <ul>
                    <li><a href="#">主题列表</a></li>
                </ul>
                <h4>文章（book）管理</h4>
                <ul>
                    <li><a href="#">文章列表</a></li>
                </ul>
                <h4>榜单（rank）管理</h4>
                <ul>
                    <li><a href="#">默认出现榜单</a></li>
                    <li><a href="#">可选榜单</a></li>
                    <li><a href="#">所有榜单</a></li>
                </ul>
                <h4>题头（Quote）管理</h4>
                <ul>
                    <li><a href="#">题头列表</a></li>
                </ul>
                <h4>作业（Homework）管理</h4>
                <ul>
                    <li><a href="{{ route('homework.create') }}">新建作业</a></li>
                    <li><a href="{{ route('homework.index') }}">作业列表</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

@stop
