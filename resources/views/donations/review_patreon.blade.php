@extends('layouts.default')
@section('title', '审核Patreon信息')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <h1>审核Patreon信息</h1>
        <div class="">
            <ul class="nav nav-tabs">
                <li role="presentation" class="{{ $show_review_tab=='not_approved'? 'active':'' }}"><a href="{{ route('donation.review_patreon', ['show_review_tab'=>'not_approved']) }}">未通过审核的Patreon信息</a></li>
                <li role="presentation" class="{{ $show_review_tab=='approved'? 'active':'' }} pull-right"><a href="{{ route('donation.review_patreon', ['show_review_tab'=>'approved']) }}">已通过审核的Patreon信息</a></li>
            </ul>
        </div>
        @include('donations._review_patreons')
        {{ $patreons->links() }}
        <div class="text-center">
            <a href="{{route('donation.upload_patreon_create')}}" class="btn btn-lg btn-primary sosad-button">批量加载patreon付款记录</a>
        </div>
    </div>
</div>
@stop
