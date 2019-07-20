@extends('layouts.default')
@section('title', '管理记录')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel-group">
            <div class="panel panel-default">
                <div class="panel-heading">
                    @if($user_name)
                    <h1>和{{ $user_name }}有关的管理记录列表</h1>
                    <a href="{{route('administrationrecords')}}" class="btn btn-md btn-danger sosad-button-control">看全站管理记录</a>
                    @else
                    <h1>全站管理记录列表</h1>
                    @if(Auth::check())
                    <a href="{{route('administrationrecords', ['user_id'=>Auth::id()])}}" class="btn btn-md btn-danger sosad-button-control">看和我有关的管理记录</a>
                    @else
                    <a href="{{route('login')}}">登陆看和我有关的管理记录</a>
                    @endif
                    @endif

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
