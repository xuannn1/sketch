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
                  <div class="col-xs-3">
                     <h5>{{ $quote->quote }}</h5>
                  </div><small>
                  <div class="col-xs-3">
                     <p><a href="#">{{ $quote->creator->name }}</a></p>
                     @if ($quote->anonymous)
                        <p>马甲：{{ $quote->majia ?? '匿名咸鱼'}}</p>
                     @endif
                     @if ($quote->notsad)
                        <p style = "color:#b73766">不丧</p>
                     @endif
                  </div>
                  <div class="col-xs-3">
                     <p>是否已审核：{{ $quote->reviewed }}</p>
                     <p>是否已通过：{{ $quote->approved }}</p>
                  </div>
                  <div class="col-xs-3">
                     @if(! $quote->approved)
                     <a class="btn btn-sm btn-success sosad-button" href="{{ route('quote.approve', $quote->id) }}" role="button">通过审核</a>
                     @else
                     <a class="btn btn-sm btn-danger" href="{{ route('quote.disapprove', $quote->id) }}" role="button">取消通过</a>
                     @endif
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
