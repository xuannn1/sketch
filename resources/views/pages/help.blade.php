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
            <div class="panel-heading">
                <span class="font-2">【{{$value1['title']}}】</span>
            </div>
            <div class="panel-body">
                @foreach($value1['children'] as $key2=>$value2)
                <div class="">
                    <div >
                        <a type="button" data-toggle="collapse" data-target="#help{{$key1}}-{{$key2}}" style="cursor: pointer;" class="font-4">
                            &nbsp;{{$key2}}&nbsp;{{$value2}}</a>
                    </div>
                    <div class="collapse" id = "help{{$key1}}-{{$key2}}">
                        <?php $QnAs = $faqs[$key1.'-'.$key2]; ?>
                        @foreach($QnAs as $i => $QnA)
                        <div class="main-text post-reply">
                            <a type="button" data-toggle="collapse" data-target="#helpQnA{{$QnA->id}}" style="cursor: pointer;" class="font-5">Q：{{$QnA->question}}</a>
                        </div>
                        <div class="collapse main-text post-reply font-5 grayout" id="helpQnA{{$QnA->id}}">
                            A：{!! StringProcess::wrapSpan($QnA->answer) !!}
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
