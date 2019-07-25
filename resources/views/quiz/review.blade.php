@extends('layouts.default')
@section('title', '驾照考审阅')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>驾照考审阅</h4>
            </div>
            <div class="panel-body">
                @foreach ($quizzes as $quiz)
                <span><strong>题目id{{$quiz->id}}</strong>|{{ $quiz->quiz_level }}级</span>
                {!! StringProcess::wrapSpan($quiz->body) !!}
                <a href="{{ route('quiz.show', $quiz->id) }}" class="btn btn-danger sosad-button btn-sm pull-right">显示详情</a>
                <!-- 各色选项 -->
                <div class="">
                    @foreach($quiz->quiz_options as $key=>$quiz_option)
                    <!-- 选项本体 -->
                    <div class="">
                        <span>选项{{ $key+1 }}：</span>
                        <span class="glyphicon {{ $quiz_option->is_correct? 'glyphicon-ok correct-option':'incorrect-option glyphicon-remove' }}">
                        <span>{!! StringProcess::wrapSpan($quiz_option->body) !!}</span>
                    </div>
                    @endforeach
                </div>
                <hr>
                @endforeach
            </div>
            {{ $quizzes->links() }}
            <br>
            <a href="{{ route('quiz.create') }}" class="btn btn-lg btn-danger sosad-button">新建题目</a>
        </div>
    </div>
</div>
@stop
