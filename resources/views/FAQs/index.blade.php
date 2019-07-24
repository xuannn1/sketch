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
                        @if(Auth::check()&&Auth::user()->isAdmin())
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
                            @if(Auth::check()&&Auth::user()->isAdmin())
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
        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="font-2">【当前参数】</span>
            </div>
            <div class="panel-body">
                <div class="">
                    <div class="">
                        <a type="button" data-toggle="collapse" data-target="#help-constants-level" style="cursor: pointer;" class="font-4">
                            &nbsp;1 升级需求：</a>
                    </div>
                    <div class="collapse" id="help-constants-level">
                        <div class="">
                            @foreach(config('level.level_up') as $level=>$level_req)
                            <div class="">
                                <span class="font-6">&nbsp;&nbsp;{{$level}}级需要：</span>
                                @foreach(config('level.values') as $key=>$value)
                                @if(array_key_exists($key,$level_req))
                                <span class="font-6 grayout">{{$value}}:{{$level_req[$key]}}，</span>
                                @endif
                                @endforeach
                            </div>
                            @endforeach
                        </div>
                        <div class="font-6">
                            注：升级检验只发生在每日签到时，每天最多升一级。
                        </div>
                    </div>
                </div>
                <div class="">
                    <a type="button" data-toggle="collapse" data-target="#help-constants-setting" style="cursor: pointer;" class="font-4">
                        &nbsp;2 系统设置：</a>
                    <div class="collapse indentation main-text" id="help-constants-setting">
                        <p>a)章节更新必须达到这个水平才能进入排名榜:{{config('constants.update_min')}}</p>
                        <p>b)“长评”必须达到该字数:{{config('constants.update_min')}}</p>
                        <p>c)一个月能修改多少次邮箱:{{config('constants.monthly_email_resets')}}</p>
                    </div>
                </div>
                @if(Auth::check())
                <div class="">
                    <a type="button" data-toggle="collapse" data-target="#help-constants-forbiddenwords-public" style="cursor: pointer;" class="font-4">
                        &nbsp;3 出现在标题/简介/章节名中会被隐藏的词汇（用‘|’隔开）：</a>
                    <div class="collapse" id="help-constants-forbiddenwords-public">
                        <div class="">
                            <img src="/img/forbidden_words.png" alt="forbidden_words">
                        </div>
                    </div>
                </div>
                <div class="">
                    <a type="button" data-toggle="collapse" data-target="#help-constants-forbiddenwords-title" style="cursor: pointer;" class="font-4">
                        &nbsp;4 出现在书名中会被隐藏的词汇（用‘|’隔开）：</a>
                    <div class="collapse" id="help-constants-forbiddenwords-title">
                        <div class="">
                            {{ config('forbiddenwords.not_in_title') }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@stop
