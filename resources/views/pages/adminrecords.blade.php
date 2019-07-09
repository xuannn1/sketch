@extends('layouts.default')
@section('title', '管理记录')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel-group">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h1>管理记录列表</h1>
                    <ul class="nav nav-tabs">
                        <li role="presentation" class="{{$record_page_set=='total'? 'active':''}}"><a href="{{ route('administrationrecords') }}">全站管理记录</a></li>
                        <li role="presentation" class="pull-right {{$record_page_set=='self'? 'active':''}}"><a href="{{ route('administrationrecords.self') }}">和我有关的管理记录</a></li>
                    </ul>
                </div>
                <div class="panel-body">
                    @foreach($records as $record)
                    <div class="">
                        <h6>
                            {{ $record->operator->name }}&nbsp;
                            {{ $record->created_at->setTimezone('Asia/Shanghai') }}&nbsp;
                            {{ config('adminoperations')[$record->operation] }}&nbsp;
                            {{ $record->record }}&nbsp;
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
