@extends('layouts.default')
@section('title', $thread->title)
@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
      @include('shared.errors')
      <div class="panel-group">
         <div class="panel panel-default">
            <div class="panel-heading">
               全部内容总括
            </div>
            <div class="panel-body text-center">
               可省略的解释
         </div>
         <div class="panel panel-default">
            <div class="panel-heading">
               比如，文章收藏
            </div>
            <div class="panel-body">
               {{links}}
               starts here, books/threads/longcomments(those are general,looking from outside)
               foreach...
               <article class="hidden-sm hidden-md hidden-lg">
                  <div class="row">
                     <div class="col-xs-12">
                        <span></span>
                        <span class="pull-right"></span>
                     </div>
                     <div class="col-xs-12">
                        <span></span>
                        <span></span>
                     </div>
                  </div>
               </article>
               endforeach
               {{links}}
            </div>
         </div>
      </div>
    </div>
</div>
@stop
