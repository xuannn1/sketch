@extends('layouts.default')
@section('title', '修改题目')

@section('content')
<div class="container-fluid">
    <div class="col-sm-offset-3 col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>修改题目</h4>
            </div>
            <div class="panel-body">
                @include('shared.errors')
                <form method="POST" action="{{ route('quiz.update', $quiz->id) }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="quiz-body"><h5>题目：</h5></label>
                        <textarea name="quiz-body" id="quiz-body" data-provide="markdown" rows="5" class="form-control" placeholder="题干放在这里">{{ $quiz->body }}</textarea>
                        <button type="button" onclick="retrievecache('quiz-body')" class="sosad-button-control addon-button">恢复数据</button>
                    </div>
                    <div class="form-group">
                        <label for="quiz-hint"><h5>答题暗示：</h5></label>
                        <textarea name="quiz-hint" rows="3" class="form-control" data-provide="markdown" placeholder="答题暗示">{{ $quiz->hint }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>
                        <label><input type="text" style="width: 80px" name="quiz-level" value="{{$quiz->quiz_level}}">题目等级</label>
                    </div>
                    <div class="form-group">
                        <label for="quiz-options"><h5>选项列表（选项|解释）：</h5></label>
                        @foreach($quiz->quiz_options as $key=>$option)
                        <div class="">
                            <textarea name="quiz-option[{{ $option->id }}]" rows="2" class="form-control" placeholder="题干">{{ $option->body }}</textarea>
                            <textarea name="quiz-option-explanation[{{ $option->id }}]" rows="2" class="form-control" placeholder="解释">{{ $option->explanation }}</textarea>
                            <label><input type="checkbox" name="check-quiz-option[{{ $option->id }}]" {{ $option->is_correct? 'checked':'' }}>属于正确选项</label>
                        </div>
                        <br>
                        @endforeach
                        <div class="">
                            <label for="quiz-options"><h5>新增选项：</h5></label>
                            <textarea name="quiz-option[0]" rows="2" class="form-control" placeholder="新增选项放这里，没有可以不填"></textarea>
                            <textarea name="quiz-option-explanation[0]" rows="2" class="form-control" placeholder="新增解释放这里，没有可以不填"></textarea>
                            <label><input type="checkbox" name="check-quiz-option[0]" >属于正确选项</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-danger sosad-button">提交</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
