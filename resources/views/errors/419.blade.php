@extends('layouts.default')
@section('title', '出错啦')

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1>页面已失效</h1>
            </div>
            <div class="panel-body">
                <h4>原因：基于废文网安全设置，超过一定时间没有活动，或浏览器多个页面同时提交，会导致用户登出和页面失效。</h4>
                <h5>请确认登陆状态，返回前置页面，刷新后重新提交（如有未保存数据，可以通过浏览器返回之后复制内容到剪贴板，或在新的对话框左下角点击“恢复数据”）。</h5>
                <h6 class="grayout">（详情代码419，这不是bug）</h6>
            </div>
        </div>
    </div>
</div>
@stop
