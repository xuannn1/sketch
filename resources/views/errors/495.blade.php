@extends('layouts.default')
@section('title', '出错啦')

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1>账户被禁止登陆</h1>
                <h4>账户被禁止登陆至：{{ $exception->getMessage()? $exception->getMessage():'次日签到时' }}</h4>
            </div>
            <div class="panel-body">
                <h4>原因：</h4>
                <h4>可能是违规导致，可从全站公共管理记录查询相关管理情况。<a href="{{ route('administrationrecords') }}">>>查看全站公共管理记录</a></h4>
                <h4>也可能是账户发生了异常活动，具有被盗号嫌疑，当前保护性禁止登陆以保障数据安全（如果是被盗号，次日签到时即可继续使用，需通过邮箱重置密码），详情见帮助“盗号”条目。</h4>
                <h6 class="grayout">（详情代码495，这不是bug）</h6>
            </div>
        </div>
    </div>
</div>
@stop
