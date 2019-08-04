@extends('layouts.default')
@section('title', '出错啦')

@section('content')
<div class="container-fluid">
    <div class="content">
        <div class="title">
            <h1>内容不存在</h1>
            <h4>解释：无法找到该内容，因而无法显示请求的页面/进行请求的操作</h4>
            <div class="font-5">
                <div class="">
                    可能的原因：
                </div>
                <ul>
                    <li>您试图激活/重制密码，实际上已经完成了激活/重置密码操作，因此token已失效</li>
                    <li>您试图删除xx内容，而内容已被自己删除</li>
                    <li>您试图访问xx内容，而内容已被原作者/管理员删除</li>
                    <li>网址路径输入错误（网址打错了）</li>
                    <li>对应内容实际上未能保存在数据库（这个内容不存在，用户未注册）</li>
                </ul>
            </div>
            <h4>解决办法：请等待缓存更新，核实相关操作是否已经完成/相关内容是否确实存在，检查确认网址拼写正确、路径数字准确，确认无误后重新从正确的入口进入页面提交数据。</h4>
            <h6>（详情代码404）</h6>
        </div>
    </div>
</div>
@stop
