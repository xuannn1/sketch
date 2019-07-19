@extends('layouts.default')
@section('title', '考试结果分析')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-body">
                <h3>废文测试题考试结果分析</h3>
                <h4>{{ $user->name }}，以下是您的答题结果分析，请再接再厉哦！</h4>
            </div>
        </div>
        @foreach($wrong_quiz as $key=>$quiz_set)
        <?php $quiz = $quiz_set['quiz']; ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <!-- 题干   -->
                <div class="h5">
                    <strong>题库qid{{ $quiz->id }}</strong>：{!! StringProcess::wrapSpan($quiz->body) !!}
                </div>
                <!-- 提示 -->
                <div class="greyout h6">
                    {!! StringProcess::wrapSpan($quiz->hint) !!}
                </div>
                <div class="">
                    正确选项：
                    @foreach($quiz_set['correct_answers'] as $answer)
                    qoid{{ $answer }},&nbsp;
                    @endforeach
                </div>
                <div class="">
                    您的选项：
                    @foreach($quiz_set['submitted_answers'] as $answer)
                    qoid{{ $answer }},&nbsp;
                    @endforeach
                </div>
            </div>
            <div class="panel-body">
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
        </div>
        @endforeach
        <div class="text-center">
            <a href="{{ route('quiz.taketest', ['level'=>$level]) }}" class="btn btn-lg btn-primary sosad-button ">重来一遍</a>
        </div>

    </div>
</div>
@stop
