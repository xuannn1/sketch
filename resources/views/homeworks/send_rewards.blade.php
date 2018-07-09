@extends('layouts.default')
@section('title', '第'.$homework->id.'次作业：奖惩')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading"><h4>{{'第'.$homework->id.'次作业：奖惩'}}</h4></div>
            <div class="panel-body">
                <form method="POST" action="{{ route('homework.rewards', $homework->id) }}">
                    {{ csrf_field() }}
                    <h4>请选择奖励情况：</h4>
                    @foreach($homework->registered as $student)
                    <div class="h6" id="rewardschoices">
                        <label for="name"><a href="{{ route('user.show', $student->id) }}" class="bigger-20">{{$student->name}}</a></label>：

                        <label class="radio-inline"><input type="radio" name="{{$student->id}}" value="1">超级奖励</label>,
                        <label class="radio-inline"><input type="radio" name="{{$student->id}}" value="2" checked>普通奖励</label>,
                        <label class="radio-inline"><input type="radio" name="{{$student->id}}" value="3">什么都不做</label>,
                        <label class="radio-inline"><input type="radio" name="{{$student->id}}" value="4">惩罚1（2月）</label>,
                        <label class="radio-inline"><input type="radio" name="{{$student->id}}" value="5">惩罚2（6月）</label>.
                    </div>
                    @endforeach
                    <br>
                    <button type="submit" class="btn btn-primary">发布</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
