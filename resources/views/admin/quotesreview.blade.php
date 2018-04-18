@extends('layouts.default')
@section('title', '审核题头')
@section('content')
<div class="container-fluid">
   <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
      <div class="panel panel-default">
         <div class="panel-heading lead">审核题头</div>
         <div class="panel-body">
            @foreach ($quotes as $quote)
               <div class="row text-center">
                  <div class="col-xs-4">
                     <h5>{{ $quote->quote }}</h5>
                  </div><small>
                  <div class="col-xs-4">
                     <p><a href="#">{{ $quote->creator->name }}</a></p>
                     @if ($quote->anonymous)
                        <p>马甲：{{ $quote->majia ?? '匿名咸鱼'}}</p>
                     @endif
                     @if ($quote->notsad)
                        <p style = "color:#b73766">不丧</p>
                     @endif
                  </div>
                  <div class="col-xs-4">
                      <button class="btn btn-small {{ $quote->approved? "btn-success":"btn-danger" }} cancel-button {{'togglereviewquote'.$quote->id}}" type="button" name="button" onClick="toggle_review_quote({{$quote->id}},'{{$quote->approved ? "disapprove":"approve"}}')">{{$quote->approved? '对外显示':'不显示'}}</button>
                  </div></small>
               </div>
               <hr>
            @endforeach
         </div>
         {{ $quotes->links() }}
      </div>
   </div>
</div>
@stop
