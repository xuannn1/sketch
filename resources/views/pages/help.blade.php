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
        @foreach(config('help') as $key1=>$value1)
        <div class="panel panel-default">
            <div class="panel-header">
                <h2>{{$key1}}.{{$value1['title']}}</h2>
            </div>
            <div class="panel-body">
                @foreach($value1['children'] as $key2=>$value2)
                <div class="">
                    <div >
                        <a type="button" data-toggle="collapse" data-target="#help{{$key1}}-{{$key2}}" style="cursor: pointer;" class="font-3">{{$key1}}-{{$key2}}.{{$value2}}</a>
                    </div>
                    <div class="collapse" id = "help{{$key1}}-{{$key2}}">
                        <?php $QnAs = $faqs[$key1.'-'.$key2]; ?>
                        @foreach($QnAs as $i => $QnA)
                        <div >
                            <a type="button" data-toggle="collapse" data-target="#helpQnA{{$QnA->id}}" style="cursor: pointer;" class="font-3">{{$QnA->question}}</a>
                        </div>
                        <div class="collapse" id="helpQnA{{$QnA->id}}" class="font-4 bigger-20">
                            {!! StringProcess::wrapSpan($QnA->answer) !!}
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

    </div>
</div>
@stop
