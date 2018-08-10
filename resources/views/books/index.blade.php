@extends('layouts.default')
@section('title', '文库')
@section('content')
<div class="container-fluid">
   <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
      <div class="panel panel-default long-foot">
         <div class="row">
             <h2 class="col-xs-10 col-xs-offset-1">
                 文章列表
             </h2>
         </div>
         @include('books._book_selector')
         <div class="panel-body">
            {{ $books->appends(request()->query())->links() }}
            @include('books._books')
            {{ $books->appends(request()->query())->links() }}
         </div>
      </div>
   </div>
</div>
@stop
