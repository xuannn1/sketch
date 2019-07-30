@extends('layouts.default')
@section('title', '搜索用户结果')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>搜索用户结果</h3>
                <h4>「用户名」含{{$name}}</h4>
                <h4>「邮箱」含{{$email}}</h4>
            </div>
        </div>
        @include('shared.errors')

        @foreach($users as $user)
        <div class="panel panel-default">
            <div class="panel-heading">
                <b>ID：</b>{{$user->id}}&nbsp;<b>昵称：</b>{{$user->name}}&nbsp;<b>email：</b>{{$user->email}}&nbsp;<b>创建时间：</b>{{$user->created_at}}
            </div>
            @foreach($user->emailmodifications as $emailmodification)
            <div class="panel-body">
                <b>修改记录ID：</b>{{$emailmodification->id}}&nbsp;<b>修改时间：</b>{{$emailmodification->created_at->setTimezone('Asia/Shanghai')}}&nbsp;<b>修改IP：</b>{{$emailmodification->ip_address}}<br>
                <b>修改token：</b>{{$emailmodification->token}}<br>
                <b>旧邮箱：</b>{{$emailmodification->old_email}}&nbsp;<b>新邮箱：</b>{{$emailmodification->new_email}}<br>
                @if($emailmodification->admin_revoked_at)
                <code>管理员于{{$emailmodification->admin_revoked_at->setTimezone('Asia/Shanghai')}}恢复至此邮箱</code><br>
                @else
                <a class="btn btn-md btn-danger sosad-button-control" href="{{route('admin.convert_to_old_email', ['user'=>$user->id,'record'=>$emailmodification->id])}}">改回旧邮箱{{$emailmodification->old_email}}</a>
                @endif
            </div>
            @endforeach

        </div>
        @endforeach

        {{$users->links()}}
    </div>
</div>
@stop
