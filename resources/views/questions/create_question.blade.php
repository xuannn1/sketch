@extends('layouts.default')
@section('title', '向'.$user->name.'提问')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
      <div class="site-map">
           <a href="{{ route('home') }}">
               <span><i class="fa fa-home"></i>&nbsp;首页</span></a>
           /
           <a href="{{ route('user.show', $user) }}">{{ $user->name }}</a>
      </div>
        <div class="panel panel-default">
            <div class="panel-body">
                @include('shared.errors')
                <form method="POST" action="{{ route('questions.store', $user) }}" name="create_question_to_user">
                    {{ csrf_field() }}
                    <div class="form-group">
                      <a href="{{ route('questions.index', $user) }}" class="h4 pull-right grayout">查看问答记录</a>
                        <label for="body"><h4>对{{ $user->name }}的问题正文：</h4></label>
                        <textarea id="mainbody" name="body" rows="12" class="form-control" data-provide="markdown" placeholder="问题正文">{{ old('body') }}</textarea>
                    </div>
                    <button type="submit" class="sosad-button-post pull-right">发布问题</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
