@extends('layouts.default')
@section('title', '出错啦')

@section('content')
<div class="container-fluid">
    <div class="content">
        <div class="title">
            <h1>数据冲突</h1>
            <h4>解释：因为一些原因，输入的数据和数据库原有数据发生了冲突，无法完成对应的操作</h4>
            <h4>可能的原因有：</h4>
            <ul class="font-5">
                <li>文章/讨论帖已经建立过，不能重复建立</li>
                <li>重复打赏、投票，或余额不足</li>
                <li>身份错误，试图修改不属于自己的数据</li>
                <li>网络连接出现问题</li>
            </ul>
            <h4>解决办法：请先核实是否已经建立了文章/讨论帖，核实输入数据，返回最初的页面并刷新后再提交数据。</h4>
            <h6>详情/参数：{{ $exception->getMessage() }}</h6>
        </div>
    </div>
</div>
@stop
