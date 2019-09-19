@extends('layouts.default')
@section('title', "全站头衔列表")

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1>
                    全站头衔列表
                </h1>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                @foreach($titles as $title)
                <div class="col-sm-4 col-xs-6">
                    <div class="panel panel-default">
                        <div class="panel-body text-center">
                            <div class="font-4">
                                <span class="maintitle title-{{$title->style_id}} title-{{$title->style_kind}}">
                                    {{$title->name}}
                                </span>
                            </div>
                            <div class="">
                                {{$title->description}}
                            </div>
                            <div class="font-6">
                                头衔ID：{{$title->id}}<br>
                                <div class="grayout">
                                    样式代码：{{$title->style_id}}<br>
                                    头衔类型：{{$title->type??'无'}}<br>
                                    类型需求：{{$title->level > 0 ? $title->level:'无'}}<br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@stop
