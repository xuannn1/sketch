@extends('layouts.default')
@section('title', '添加帮助条目')

@section('content')
<div class="container-fluid">
    <div class="col-sm-offset-3 col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>对「{{config('faq')[$keys[0]]['children'][$keys[1]]}}」分类添加帮助条目</h3>
            </div>
            <div class="panel-body">
                @include('shared.errors')
                <form method="POST" action="{{ route('faq.store') }}">
                    {{ csrf_field() }}
                    <input type="text" name="key" class="hidden" value="{{$keys[0]}}-{{$keys[1]}}">

                    <div class="form-group">
                        <label for="question"><h5>新问题：</h5></label>
                        <textarea name="question" id="question" rows="2" class="form-control" placeholder="问题放在这里">{{ old('question') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="answer"><h5>新答案：</h5></label>
                        <textarea name="answer" rows="10" class="form-control" data-provide="markdown" placeholder="对问题的回答">{{ old('answer') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-danger sosad-button">提交</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
