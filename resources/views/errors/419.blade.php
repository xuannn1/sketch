@extends('layouts.default')
@section('title', '出错啦')

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1>你已登出或页面已失效</h1>
            </div>
            <div class="panel-body">
                <h4>原因：基于废文网安全设置，超过一定时间没有活动，或浏览器同时使用多个输入信息提交页面，会导致用户登出和页面失效。</h4>
                <h4>常规排查途径：</h4>
                <h4>请看右上角，如果从之前的登入状态变成登出状态（右上角显示“注册+登陆”），说明你被登出，请重新登陆并勾选“记住我”。</h4>
                <h4>如果右上角仍是登陆状态，只是不能提交数据，可能是浏览器cookie失活或页面失效，请尝试“清理cookie”（在常用搜索引擎中输入自己机型/浏览器型号+“清理cookie”，如“小米浏览器清理cookie”，按照指示操作即可）。本操作只需进行一次。</h4>
                <h4>也可以尝试更改成其他可用域名，当前可用域名列表：<code>sosad.fun</code>，<code>wenzhan.org</code>，<code>sosadfun.com</code>，任选其一使用即可。</h4>
                <h4>如果仍然频繁出现登出问题，建议重新安装浏览器，或更换其他浏览器。</h4>
                <h6 class="grayout">（详情代码419，这不是bug）</h6>
            </div>
        </div>
    </div>
</div>
@stop
