@extends('layouts.default')
@section('title', '出错啦')

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1>您已登出或页面已失效</h1>
            </div>
            <div class="panel-body">
                <h4>原因：基于废文网安全设置，超过一定时间没有活动，或浏览器同时使用多个输入信息提交页面，会导致用户登出和页面失效。</h4>
                <h4></h4>
                <h4>解决办法：请重新登陆、重新提交数据，在登陆时勾选“记得我”。如果频繁出现登出问题，也有可能是浏览器当前设置不兼容安全cookie导致，请修改浏览器安全设置或更换浏览器。</h4>
                <h6 class="grayout">（详情代码419，这不是bug）</h6>
            </div>
        </div>
    </div>
</div>
@stop
