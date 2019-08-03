@extends('layouts.default')
@section('title', '出错啦')

@section('content')
<div class="container-fluid">
    <div class="content text-center">
        <div class="title">
            <h1>内容不存在</h1>
            <h4>无法找到该内容，因而无法显示请求的页面</h4>
            <div class="font-5">
                <p>可能的原因如下：</p>
                <ul>
                    <li>网址路径错误</li>
                    <li>内容已被删除</li>
                    <li>对应内容实际上未能保存在数据库</li>
                </ul>
            </div>
            <h6>备注：错误代码404</h6>
        </div>
    </div>
</div>
@stop
