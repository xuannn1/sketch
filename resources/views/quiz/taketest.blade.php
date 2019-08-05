@extends('layouts.default')
@section('title', '考试试题')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1>废文使用测试题</h1>
                <h4>{{ $user->name }} 您好！欢迎您参与废文使用测试！在这里您将彻底学习如何做条好鱼。每位咸鱼初次答对全部题目时，还会获得<code>升级</code>必备的分值<code>奖励</code>，还等什么呢，快来尝试一下吧！</h4>
                <h3>当前题目等级：{{$level+1}}级</h3>
            </div>
            <div class="panel-body">
                @include('shared.errors')
                <form method="POST" action="{{ route('quiz.submittest') }}">
                    {{ csrf_field() }}
                    <input name="level" value="{{$level}}" class="hidden">
                    @foreach ($quizzes as $quiz_key=> $quiz)
                    <div class="h4">
                        <span><strong>第{{ $quiz_key+1 }}题：</strong></span>
                        <input type="text" name="quiz-answer[{{ $quiz->id }}][quiz_id]" class="hidden form-control" value="{{ $quiz->id }} ">
                    </div>
                    <div class="h4">
                        {!! StringProcess::wrapSpan($quiz->body) !!}
                    </div>
                    @if($quiz->hint)
                    <div class="text-center">
                        <a type="button" data-toggle="collapse" data-target="#quiz-hint{{ $quiz->id }}" style="cursor: pointer;" class="h6">点击查看答题提示</a>
                    </div>
                    <div class="collapse grayout h6" id = "quiz-hint{{ $quiz->id }}">
                        {!! StringProcess::wrapSpan($quiz->hint) !!}
                    </div>
                    @endif
                    <!-- 各色选项 -->
                    <div class="">
                        @foreach($quiz->random_options as $option_key=>$quiz_option)
                        <!-- 选项本体 -->
                        <div class="">
                            <label><input type="checkbox" name="quiz-answer[{{ $quiz->id }}][{{ $quiz_option->id }}]"><span>选项{{ $option_key+1 }}：</span><span>{!! StringProcess::wrapSpan($quiz_option->body) !!}</span></label>
                        </div>
                        @endforeach
                    </div>
                    <hr>
                    @endforeach
                    <button type="submit" class="btn btn-md btn-danger sosad-button">提交</button>
                </form>
            </div>
            <br>
            <h6 class="grayout">(答题功能刚制作完成，题库还在填充中，目前只提供基础答题，更多题目和其他分值奖励，请等待新系统出现</h6>
            <h6 class="grayout">也欢迎热心咸鱼前往<code>出题</code>： <a href="route('thread.show', 13505)">《废文测试题征集楼》</a>，不光可以帮（捉）助（弄）新鱼，一经录用还有<code>升级必备虚拟物</code>的奖励哦～还等什么呢！  )</h6>
        </div>
    </div>
</div>
@stop
