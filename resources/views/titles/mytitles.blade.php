@extends('layouts.default')
@section('title', $user->name."的头衔中心")

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1>
                    「{{$user->name}}」的头衔中心
                </h1>
                <h5 class="text-center">
                    当前头衔：
                    <span class="maintitle title-{{$user->title->style_id}}">
                        {{$user->title->name}}
                    </span>
                </h5>
                <h6 class="text-center">
                    头衔佩戴更改后，需要几分钟的缓存时间才会显示哦～
                </h6>
            </div>
        </div>
        <div class="text-center font-3">
            Lv{{$user->level}}可佩戴头衔
        </div>
        <div class="container-fluid">
            <div class="row">
                @foreach($default_titles as $title)
                @if($title->id<=$user->level+1)
                <div class="col-sm-4 col-xs-6">
                    <div class="panel panel-default">
                        <div class="panel-body text-center">
                            <div class="font-4">
                                <span class="maintitle title-{{$title->style_id}}">
                                    {{$title->name}}
                                </span>
                            </div>
                            <div class="">
                                {{$title->description}}
                            </div>
                            @if($title->id!=$user->title_id)
                            <a href="{{route('title.wear',$title->id)}}" class="btn btn-md btn-primary sosad-button-control">佩戴头衔</a>
                            @else
                            <a href="{{route('title.wear',0)}}" class="btn btn-md btn-primary sosad-button">取消佩戴</a>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
                @endforeach
            </div>
            <div class="text-center">
                <p>更多头衔和任务在路上啦～</p>
            </div>
        </div>

    </div>
</div>
@stop
