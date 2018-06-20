@extends('layouts.default')
@section('title', '向'.$user->name.'提问')
@section('content')
<div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
    <div>
        <a type="btn btn-primary" href="{{ route('home') }}"><span class="glyphicon glyphicon-home"></span><span>首页</span></a>/<a href="{{ route('user.show', $user) }}">{{ $user->name }}</a>
    </div>
    <div class="panel panel-default">
        <div class="panel-body">
            @include('shared.errors')
            <form method="POST" action="{{ route('questions.store', $user) }}" name="create_question_to_user">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="body"><h4>对{{ $user->name }}的问题正文：</h4></label>
                    <a href="{{ route('questions.index', $user) }}" class="h4 pull-right">查看问答记录</a>
                    <textarea id="mainbody" name="body" rows="12" class="form-control" data-provide="markdown" placeholder="问题正文">{{ old('body') }}</textarea>
                    <br>
                </div>
                <button type="submit" class="btn btn-primary sosad-button">发布问题</button>
            </form>
        </div>
    </div>
</div>
@stop
