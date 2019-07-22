@extends('layouts.default')
@section('title', '修改帮助条目')

@section('content')
<div class="container-fluid">
    <div class="col-sm-offset-3 col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>修改「{{config('faq')[$keys[0]]['children'][$keys[1]]}}」分类下的帮助条目</h3>
            </div>
            <div class="panel-body">
                @include('shared.errors')
                <form method="POST" action="{{ route('faq.update', $faq->id) }}">
                    {{ csrf_field() }}
                    @method('PATCH')
                    <div class="form-group">
                        <label for="question"><h5>问题：</h5></label>
                        <textarea name="question" id="question" rows="2" class="form-control" placeholder="问题放在这里">{{ $faq->question }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="answer"><h5>答案：</h5></label>
                        <textarea name="answer" rows="10" class="form-control" data-provide="markdown" placeholder="答案">{{ $faq->answer }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-danger sosad-button">提交</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
