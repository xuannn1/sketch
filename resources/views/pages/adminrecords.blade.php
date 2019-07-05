@extends('layouts.default')
@section('title', '管理记录')
@section('content')
<div class="container">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel-group">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h1>管理记录列表</h1>
                    <ul class="nav nav-tabs">
                        <li role="presentation" class="{{$active==1? 'active':''}}"><a href="{{ route('administrationrecords') }}">全部管理记录</a></li>
                        <li role="presentation" class="pull-right {{$active==2? 'active':''}}"><a href="{{ route('administrationrecords.self') }}">和我有关的管理记录</a></li>
                    </ul>
                </div>
                <div class="panel-body">
                    @foreach($records as $record)
                    <div class="">
                        <h6>
                            <a href="{{ route('user.show', $record->user_id) }}">{{ $record->name }}</a>
                            &nbsp;
                            {{ Carbon\Carbon::parse($record->created_at)->setTimezone('Asia/Shanghai') }}
                            {{ $admin_operation[$record->operation] }}
                            {!! $record->operated_users_name.' '.Helper::trimtext($record->thread_title.$record->post_body.$record->postcomment_body.$record->status_body,30) !!}
                            &nbsp;
                            原因：{{ $record->reason }}
                        </h5>
                    </div>
                    @endforeach
                </div>
                {{ $records->links() }}
            </div>
        </div>
    </div>
</div>


@stop
