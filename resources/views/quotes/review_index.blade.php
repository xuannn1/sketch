@extends('layouts.default')
@section('title', '审核题头')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1>审核题头</h1>
                <h5 class="text-center"><code>绿色表示该题头目前 "对外显示" ，红色表示 "不对外显示
                    " 。</code>
                </h5>
                <div class="">
                    <ul class="nav nav-tabs">
                        <li role="presentation" class="{{ $quote_review_tab==='notYetReviewed'? 'active':'' }}"><a href="{{ route('quote.review_index', ['withReviewState'=>'notYetReviewed']) }}">未审核题头</a></li>
                        <li role="presentation" class="{{ $quote_review_tab==='all'? 'active':'' }} pull-right"><a href="{{ route('quote.review_index', ['withReviewState'=>'all']) }}">全部题头</a></li>
                    </ul>

                </div>
            </div>
            <div class="panel-body">
                {{ $quotes->links() }}
                @include('quotes._quotes_review')
                {{ $quotes->links() }}
            </div>

        </div>
    </div>
</div>
@stop
