@extends('layouts.default')
@section('title', ' 重置密码/重新激活')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading h5">
                    向您的邮箱发送 <strong>重置密码/重新激活</strong> 邮件
                </div>
                <div class="panel-body">
                    @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                    @endif
                    <form action="{{ route('password.email') }}" method="POST">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label class="col-md-4 control-label">邮箱地址:</label>
                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                            </div>
                        </div>
                        <label for="" class="h6">(有时重置邮件会发送至个人垃圾箱，请注意查看。请注意,<code>不要重复点击</code>“重置”。重复发送邮件会被邮箱系统判断为<code>垃圾邮件</code>拒收。)如果按钮无法打开或无法激活，请尝试清理浏览器缓存，或复制链接到其他浏览器内打开完成激活。</label>
                        <button type="submit" class="btn btn-danger sosad-button">重置</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
