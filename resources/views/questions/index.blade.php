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
                    <a href="{{ route('questions.index', $user) }}" class="h4">{{ $user->name }}的问答记录</a>
                </div>
            </div>
            <div class="panel-body">
                @foreach ($questions as $question)
                <div class="row {{ $question->answer_id>0? 'grayout':'' }} main-text ">
                    <div class="col-xs-2">
                        {{ Carbon::parse($question->created_at)->diffForHumans() }}提问
                    </div>
                    <div class="col-xs-10">
                        {!! StringProcess::wrapParagraphs($question->question_body) !!}
                        @if((Auth::check())&&(Auth::id()==$user->id)&&($question->answer_id==0))
                        <button onclick="document.getElementById('answeringquestion{{$question->id}}').style.display = 'block'" class="btn btn-primary btn-sm sosad-button pull-right">回答</button>
                        @endif
                    </div>
                </div>
                @if($question->answer_id>0)
                <div class="row main-text">
                    <div class="col-xs-2">
                        {{ Carbon::parse($question->answer->created_at)->diffForHumans() }}回答
                        @if($question->answer->updated_at > $question->answer->created_at)
                        {{ Carbon::parse($question->answer->updated_at)->diffForHumans() }}修改
                        @endif
                    </div>
                    <div class="col-xs-10">
                        {!! StringProcess::wrapParagraphs($question->answer->answer_body) !!}
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
        @if(Auth::check()&&Auth::user()->user_level>1)
        <div class="panel panel-default">
            <div class="panel-body">
                @include('shared.errors')
                <form method="POST" action="{{ route('questions.store', $user) }}" name="create_question_to_user">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="body"><h4>对{{ $user->name }}的新问题：</h4></label>
                        <label class="grayout">（提问箱是匿名提问，请提对用户本身的好奇疑问，注意言辞友善。请不要通过提问箱进行站务处理/举报/站内使用答疑/求邀请码。滥用提问箱将导致禁言封号。）</label>
                        <textarea id="mainbody" name="body" rows="3" class="form-control" data-provide="markdown" placeholder="问题正文">{{ old('body') }}</textarea>
                        <br>
                    </div>
                    <button type="submit" class="btn btn-primary sosad-button">发布问题</button>
                </form>
            </div>
        </div>
        @else
        <div class="text-center">
            <h6 class="display-4 grayout">抱歉，2级及以上用户才可提问，您的等级不足</h6>
        </div>
        @endif
    </div>
</div>

@stop
