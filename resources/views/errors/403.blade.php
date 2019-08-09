@extends('layouts.default')
@section('title', '数据冲突')

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1>权限不足</h1>
                <h4>解释：您的权限不足，无法访问当前页面或进行当前操作。</h4>
            </div>
            <div class="panel-body">
                <h4>可能的原因有：</h4>
                <ul class="font-5">
                    <li>作者“隐藏”了文章/讨论帖，只有作者本人才可以访问（标题侧边会出现眼睛标记）。</li>
                    <li>这个板块属于私密板块，只对特殊用户开放</li>
                    <li>您未登陆，或切换了另外的账户以致未能保持正确的登陆身份</li>
                </ul>
                <h4>解决办法：请检查是否仍是登陆状态，核对网址是否正确，确认无误后重新从正确的入口进入页面提交数据。请勿直接返回/刷新。</h4>
                <h6 class="grayout">（详情代码403，这不是bug）</h6>
            </div>
        </div>
    </div>
</div>
@stop
