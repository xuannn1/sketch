@extends('layouts.default')
@section('title', '审核题头')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>审核题头</h4>
                <h5 class="text-center"><code>绿色表示该题头目前 "对外显示" ，红色表示 "不对外显示
                    " 。</code></h5>
                </div>
                <div class="panel-body">
                    @foreach ($quotes as $quote)
                    <div class="row text-center">
                        <div class="col-xs-7">
                            <h5>{{ $quote->quote }}</h5>
                        </div>
                        <small>
                            <div class="col-xs-2">
                                <p><a href="#">{{ $quote->creator->name }}</a></p>
                                @if ($quote->anonymous)
                                <p>马甲：{{ $quote->majia ?? '匿名咸鱼'}}</p>
                                @endif
                                @if ($quote->notsad)
                                <p style = "color:#b73766">不丧</p>
                                @endif
                                @if(!$quote->reviewed)
                                <span class="not_reviewed_{{ $quote->id }}"><code>未审核</code></span>
                                @endif
                                <span class="quotereviewstatus{{ $quote->id }}">{{$quote->approved? '对外显示':'不对外显示'}}</span>
                            </div>
                            <div class="col-xs-3 quotebutton{{$quote->id}}">
                                @if(!$quote->notsad)
                                    <button class="btn btn-small btn-success cancel-button approvebutton{{$quote->id}} {{$quote->reviewed? 'hidden':''}}"  type="button" name="button" onClick="toggle_review_quote({{$quote->id}},'approve')">对外显示</button>
                                    <button class="btn btn-small btn-danger cancel-button disapprovebutton{{$quote->id}} {{$quote->reviewed? 'hidden':''}}"  type="button" name="button" onClick="toggle_review_quote({{$quote->id}},'disapprove')">不显示</button>
                                    <button class="btn btn-small btn-info cancel-button togglebutton{{$quote->id}} {{$quote->reviewed? '':'hidden'}}"  type="button" name="button" onClick="toggle_re_review_buttons({{$quote->id}},{{$quote->approved}})">重新审核</button>
                                @endif
                            </div></small>
                        </div>
                        <hr>
                        @endforeach
                    </div>
                    {{ $quotes->links() }}
                </div>
            </div>
        </div>
        @stop
