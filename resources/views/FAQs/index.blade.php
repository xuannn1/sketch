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
        @foreach(config('faq') as $key1=>$value1)
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
                        @if(Auth::user()->isAdmin())
                        <span>
                            <a href="{{route('faq.create',['key'=>$key1.'-'.$key2])}}" class="pull-right smaller-20 admin-symbol">添加<i class="fa fa-plus" aria-hidden="true"></i></a>
                        </span>
                        @endif
                    </div>
                    <div class="collapse" id = "help{{$key1}}-{{$key2}}">
                        <?php $QnAs = $faqs[$key1.'-'.$key2]; ?>
                        @foreach($QnAs as $i => $QnA)
                        <div class="main-text post-reply">
                            <a type="button" data-toggle="collapse" data-target="#helpQnA{{$QnA->id}}" style="cursor: pointer;" class="font-5">Q：{{$QnA->question}}</a>
                            @if(Auth::user()->isAdmin())
                            <span>
                                &nbsp;&nbsp;&nbsp;<a href="{{route('faq.edit', $QnA->id)}}" class="smaller-20 admin-symbol">修改本条<i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                            </span>
                            @endif
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
