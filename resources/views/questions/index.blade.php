@extends('layouts.default')
@section('title', $user->name.'的提问箱')
@section('content')
@include('shared.errors')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div>
            <a type="btn btn-primary" href="{{ route('home') }}"><span class="glyphicon glyphicon-home"></span><span>首页</span></a>/<a href="{{ route('user.show', $user) }}">{{ $user->name }}</a>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <div>
                    <a href="{{ route('questions.index', $user) }}" class="h4">{{ $user->name }}问答记录</a>
                    <a class="h4 pull-right" href="{{ route('questions.create', $user) }}">提新问题</a>

                </div>
            </div>
            <div class="panel-body">
                @foreach ($questions as $question)
                <div class="row {{ $question->answer_id>0? 'grayout':'' }} main-text ">
                    <div class="col-xs-2">
                        {{ Carbon\Carbon::parse($question->created_at)->diffForHumans() }}提问
                    </div>
                    <div class="col-xs-10">
                        {!! Helper::wrapParagraphs($question->question_body) !!}
                        @if((Auth::check())&&(Auth::id()==$user->id)&&($question->answer_id==0))
                            <button onclick="document.getElementById('answeringquestion{{$question->id}}').style.display = 'block'" class="btn btn-primary btn-sm sosad-button pull-right">回答</button>
                        @endif
                    </div>
                </div>
                @if($question->answer_id>0)
                    <div class="row main-text">
                        <div class="col-xs-2">
                            {{ Carbon\Carbon::parse($question->answer->created_at)->diffForHumans() }}回答
                            @if($question->answer->updated_at > $question->answer->created_at)
                            {{ Carbon\Carbon::parse($question->answer->updated_at)->diffForHumans() }}修改
                            @endif
                        </div>
                        <div class="col-xs-10">
                            {!! Helper::wrapParagraphs($question->answer->answer_body) !!}
                            @if((Auth::check())&&(Auth::id()==$user->id))
                            <button onclick="document.getElementById('answeringquestion{{$question->id}}').style.display = 'block'" class="btn btn-primary btn-sm sosad-button pull-right">修改回答</button>
                            @endif
                        </div>
                    </div>
                @endif
                <div id="answeringquestion{{$question->id}}" style="display:none">
                    <form method="POST" action="{{ route('questions.answer', ['user'=>$user,'question'=>$question]) }}" name="create_answer_to_question">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <textarea id="answerofquestion{{$question->id}}" name="body" rows="10" class="form-control" data-provide="markdown" placeholder="问题正文">{{ $question->answer? $question->answer->answer_body : old('body') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary sosad-button">发布回答</button>
                        <br>
                    </form>
                </div>
                <hr>
                @endforeach
                {{ $questions->links() }}
            </div>
        </div>
    </div>
</div>
@stop
