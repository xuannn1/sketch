@extends('layouts.default')
@section('title', '驾照考题')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><a href="{{ route('quiz.review') }}">题库目录</a></h4>
            </div>
            <div class="panel-body">
                <div class="text-center">
                    <h5>题目qid{{ $quiz->id }}，</h5>
                    <h6>（题目等级 {{ $quiz->quiz_level }}，已答 {{ $quiz->quiz_counts}}次，正确回答 {{  $quiz->correct_counts }}次。） </h6>
                    <a href="{{ route('quiz.edit', $quiz->id) }}" class="btn btn-danger sosad-button">修改本题</a>
                </div>
                <!-- 题干   -->
                <div class="">
                    {!! StringProcess::wrapSpan($quiz->body) !!}
                </div>
                <!-- 提示 -->
                <div class="greyout h6">
                    {!! StringProcess::wrapSpan($quiz->hint) !!}
                </div>
                <br>
                <!-- 各色选项 -->
                <div class="">
                    @foreach($quiz->quiz_options as $quiz_option)
                    <!-- 选项本体 -->
                    <div class="">
                        <span class="glyphicon {{ $quiz_option->is_correct? 'glyphicon-ok correct-option':'incorrect-option glyphicon-remove' }}">选项</span>
                        <span class="grayout h6">qoid{{ $quiz_option->id }}</span>
                        <span>{!! StringProcess::wrapSpan($quiz_option->body) !!}</span>
                    </div>
                    <div class="grayout h6">
                        <span>{!! StringProcess::wrapSpan($quiz_option->explanation) !!}</span>
                    </div>
                    @endforeach

                </div>
            </div>
            <br>
        </div>
    </div>
</div>
@stop
