@extends('layouts.default')
@section('title', '打赏列表')

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-header text-center">
                @switch($rewardable_type)
                @case('post')
                <div class="font-4">
                    <a href="{{ route('post.show', $rewarded_model->id) }}">{{  $rewarded_model->brief }}</a>
                </div>
                @break

                @case('thread')
                <div class="font-2">
                    <a href="{{ route('thread.show', $rewarded_model->id) }}">《{{ $rewarded_model->title}}》</a>
                </div>
                <div class="font-4">
                    {{ $rewarded_model->brief}}
                </div>

                @break

                @case('status')
                Second case...
                @break

                @case('quote')
                <div class="font-4">
                    {{$rewarded_model->id}}号题头
                </div>
                <div class="font-2">
                    <a href="{{ route('quote.show', $rewarded_model->id) }}">
                        {{ $rewarded_model->body}}
                    </a>
                </div>


                @break

                @default
                未完成
                @endswitch
                <div class="font-4">
                    打赏列表
                </div>
            </div>
            <hr>
            <div class="panel-body">
                {{ $rewards->links() }}
                @include('rewards._brief_rewards')
                {{ $rewards->links() }}
            </div>
        </div>
    </div>
</div>
@stop
