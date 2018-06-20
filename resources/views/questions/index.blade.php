@extends('layouts.default')
@section('title', '您的提问箱')
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>对您提出的全部问题</h4>
                <div class="panel-body">
                    @foreach ($questions as $question)
                    <div class="col-xs-3">
                        {{ Carbon\Carbon::parse($question->created_at)->diffForHumans() }}
                    </div>
                    <div class="col-xs-9">
                        {!! Helper::wrapParagraphs($question->question_body) !!}
                    </div>
                    <hr>
                    @endforeach
                    {{ $questions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@stop
