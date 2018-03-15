@extends('layouts.default')
@section('title', '关于')
@section('content')
<div class="container">
   <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
      <div class="panel-group">
         <div class="panel panel-default">
            <div class="panel-heading">
               <div class="container-fluid">
                  <div class="row">
                     <div class="col-xs-10 col-xs-offset-1">
                        <h1>关于</h1>
                        test
                     </div>
                  </div>
               </div>
            </div>
            <div class="panel-body">
               <div class="container-fluid">
                  <div class="row">
                     <div class="col-xs-10 col-xs-offset-1">
                        {!! Helper::sosadMarkdown($data['webinfo_about']) !!}
                     </div>
                  </div>
               </div>
            </div>
            <br>
            <br>
         </div>
      </div>
   </div>
</div>


@stop
