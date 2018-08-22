@extends('layouts.default')
@section('title', $user->name.'的提问箱')
@section('content')
@include('shared.errors')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
          <div class="site-map">
               <a href="{{ route('home') }}">
                   <span><i class="fa fa-home"></i>&nbsp;首页</span></a>
               /
               <a href="{{ route('user.show', $user) }}">{{ $user->name }}</a>
          </div>

        <div class="panel panel-default">
          <div class="panel-heading">
            <div>
              <a href="{{ route('questions.index', $user) }}" class="h4">{{ $user->name }} 问答记录</a>

            </div>
          </div>
            <div class="panel-body">
              @if(!(Auth::check())||!(Auth::id()==$user->id))
              <div class="row text-center">
                <a class="sosad-button-thread width100" href="{{ route('questions.create', $user) }}">
                  <i class="fas fa-hand-paper"></i>
                  提新问题
                </a>
              </div>
              @endif
                @foreach ($questions as $question)
                <div class="row {{ $question->answer_id>0? 'grayout':'' }} main-text">
                    <div class="margin5 smaller-10 grayout">
                      ·
                        {{ Carbon\Carbon::parse($question->created_at)->diffForHumans() }}提问
                      ·
                    </div>
                    <div class="margin5 text-center">
                      <span>
                        <i class="fas fa-quote-left"></i>&nbsp;——&nbsp;&nbsp;
                      </span>
                      <p></p>
                      {!! Helper::wrapParagraphs($question->question_body) !!}
                      <span>
                        &nbsp;&nbsp;——&nbsp;<i class="fas fa-quote-right"></i>
                      </span>
                      @if((Auth::check())&&(Auth::id()==$user->id)&&($question->answer_id==0))
                          <button onclick="document.getElementById('answeringquestion{{$question->id}}').style.display = 'block'" class="btn-sm sosad-button-tag pull-right">回答</button>
                      @endif
                    </div>
                </div>
                @if($question->answer_id>0)
                    <div class="row main-text">
                        <div class="margin5 smaller-10 grayout">
                          ·
                            {{ Carbon\Carbon::parse($question->answer->created_at)->diffForHumans() }}回答
                            @if($question->answer->updated_at > $question->answer->created_at)
                            {{ Carbon\Carbon::parse($question->answer->updated_at)->diffForHumans() }}修改
                            @endif
                          ·
                        </div>
                        <div class="margin5">
                            {!! Helper::wrapParagraphs($question->answer->answer_body) !!}
                            @if((Auth::check())&&(Auth::id()==$user->id))
                            <button onclick="document.getElementById('answeringquestion{{$question->id}}').style.display = 'block'" class="btn-sm sosad-button-tag pull-right">修改回答</button>
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
                        <div class="text-right">
                          <button type="submit" class="sosad-button-post">发布回答</button>
                        </div>
                        <br>
                    </form>
                </div>
                @endforeach
                {{ $questions->links() }}
            </div>
        </div>
    </div>
</div>
@stop
