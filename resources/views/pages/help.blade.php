@extends('layouts.default')
@section('title', "帮助")

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-body text-center">
                <h1>帮助中心</h1>
            </div>
        </div>
        @foreach(config('help') as $key=>$value)
        <div class="panel panel-default">
            <div class="panel-header">
                <h2>{{$key}}.{{$value['title']}}</h2>
            </div>
            <div class="panel-body">
                @foreach($value['children'] as $key2=>$value2)
                <div class="">
                    <div >
                        <a type="button" data-toggle="collapse" data-target="#help{{$key}}-{{$key2}}" style="cursor: pointer;" class="font-3">{{$key}}-{{$key2}}.{{$value2}}</a>
                    </div>
                    <div class="collapse" id = "help{{$key}}-{{$key2}}">
                        {{$key}}-{{$key2}}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

    </div>
</div>
@stop
