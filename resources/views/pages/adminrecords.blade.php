@extends('layouts.default')
@section('title', '管理记录')
@section('content')
<div class="container">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel-group">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h1>管理记录列表</h1>
                </div>
                <div class="panel-body">
                    @foreach($records as $record)
                    <div class="">
                        <h5>
                            <a href="{{ route('user.show', $record->user_id) }}">{{ $record->name }}</a>
                            &nbsp;
                            {{ $admin_operation[$record->operation] }}
                            {!! Helper::trimtext($record->thread_title.$record->post_body.$record->postcomment_body.$record->operated_users_name,20) !!}
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
