@extends('layouts.default')
@section('title', '数据冲突')

@section('content')
<div class="container-fluid">
    <div class="content">
        <div class="title">
            <h1>权限不足</h1>
            <h4>解释：您的权限不足，无法访问当前页面或进行当前操作。</h4>
            <h4>解决办法：请检查是否仍是登陆状态，核对网址是否正确，返回原始页面刷新</h4>
            <h6>（详情代码403）</h6>
        </div>
    </div>
</div>
@stop
