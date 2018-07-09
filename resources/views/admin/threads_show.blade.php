@extends('layouts.default')
@section('title', '管理界面')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading"><h4>待办事</h4></div>
            <div class="panel-body">
                <div class="container-fluid">
                    @foreach($threads as $thread)
                    <div class="row">
                        <span><a href="{{ route('thread.show',$thread) }}">{{Helper::convert_to_title($thread->title)}}</a></span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@stop
