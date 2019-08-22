@extends('layouts.default')
@section('title', '出错啦')

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1>内容不存在</h1>
                <h4>解释：无法找到该内容，因而无法显示请求的页面/进行请求的操作</h4>
            </div>
            <div class="panel-body">
                <div class="font-5">
                    <div class="">
                        可能的原因：
                    </div>
                    <ul>
                        <li>你试图激活/重置密码，实际上已经完成了激活/重置密码操作，无需重复操作，因此token已失效</li>
                        <li>你试图查看刚建立的文章/帖子/章节，内容已经保存但缓存尚未更新，暂时看不见。</li>
                        <li>你试图删除xx内容，删除请求已经提交，因此无法访问</li>
                        <li>你试图访问xx内容，而内容已被原作者/管理员删除</li>
                        <li>网址路径输入错误（网址打错了）</li>
                        <li>对应内容实际上未能保存在数据库（这个内容不存在，用户未注册）</li>
                    </ul>
                </div>
                <h4>解决办法：请等待缓存更新，核实相关操作是否已经完成/相关内容是否确实存在，检查确认网址拼写正确、路径数字准确，确认无误后重新从正确的入口进入页面提交数据。</h4>
                <h6 class="grayout">（详情代码404，这不是bug）</h6>
            </div>
        </div>
    </div>
</div>
@stop
