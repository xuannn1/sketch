@extends('layouts.default')
@section('title', '添加题目')

@section('content')
<div class="container-fluid">
    <div class="col-sm-offset-3 col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>添加题目</h4>
            </div>
            <div class="panel-body">
                @include('shared.errors')
                <form method="POST" action="{{ route('quiz.store') }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="quiz-body"><h5>新题目：</h5></label>
                        <textarea name="quiz-body" id="quiz-body" data-provide="markdown" rows="5" class="form-control" placeholder="题干放在这里">{{ old('quiz-body') }}</textarea>
                        <button type="button" onclick="retrievecache('quiz-body')" class="sosad-button-control addon-button">切换恢复数据</button>
                    </div>
                    <div class="form-group">
                        <label for="quiz-hint"><h5>答题暗示：</h5></label>
                        <textarea name="quiz-hint" rows="3" class="form-control" data-provide="markdown" placeholder="答题暗示">{{ old('quiz-hint') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>
                        <label><input type="text" style="width: 80px" name="quiz-level" value="0">题目等级</label>
                    </div>
                    <div class="form-group">
                        <label for="quiz-options"><h5>选项列表（选项|解释）：</h5></label>
                        @for ($i = 0; $i < 5; $i++)
                        <div class="">
                            <textarea name="quiz-option[{{ $i }}]" rows="2" class="form-control" placeholder="题干"></textarea>
                            <textarea name="quiz-option-explanation[{{ $i }}]" rows="2" class="form-control" placeholder="解释"></textarea>
                            <label><input type="checkbox" name="check-quiz-option[{{ $i }}]">属于正确选项</label>
                        </div>
                        <br>
                        @endfor
                    </div>
                    <button type="submit" class="btn btn-lg btn-danger sosad-button">提交</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
