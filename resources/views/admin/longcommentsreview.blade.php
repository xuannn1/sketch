@extends('layouts.default')
@section('title', '审核长评')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>审核长评</h4>
                <h5>暂定标准：树洞吐槽不发，生活感悟发积极乐观的，书评拉踩谨慎选择，鼓励乐观的多发。</h5>
                </div>
                <div class="panel-body">
                    {{$posts->links()}}
                    @include('posts._posts')
                    {{$posts->links()}}
                </div>
            </div>
        </div>
    </div>
</div>
@stop
