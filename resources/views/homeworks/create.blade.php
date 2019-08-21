@extends('layouts.default')
@section('title', '发布新作业')
@section('content')
<div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>发布新作业</h4>
        </div>
        <div class="panel-body">
            @include('shared.errors')

            <form method="POST" action="{{ route('homework.store') }}">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="requirement">作业要求：</label>
                    <textarea name="requirement" id="requirement" rows="12" class="form-control" data-provide="markdown" placeholder="作业时间-流程……etc">{{ old('requirement') }}</textarea>
                    <button type="button" onclick="retrievecache('requirement')" class="sosad-button-control addon-button">切换恢复数据</button>
                    <button href="#" type="button" onclick="wordscount('requirement');return false;" class="pull-right sosad-button-control addon-button">字数统计</button>

                </div>
                <div class="">
                    <label for="hold_sangdian">抵押丧点<input type="number" name="hold_sangdian" min="0" max="500">，</label>
                    <label for="register_number">报名人数<input type="number" name="register_number" min="5" max="50">，</label>
                    <label for="start_time">开始报名时间<input id="start_time" type="datetime-local" name="start_time" value="{{ Carbon::now('Asia/Shanghai')->format('Y-m-d\TH:i') }}">，</label>

                </div>
            <button type="submit" class="btn btn-lg btn-danger sosad-button">发布</button>
        </form>
    </div>
</div>
</div>

@stop
