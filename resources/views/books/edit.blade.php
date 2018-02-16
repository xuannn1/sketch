@extends('layouts.default')
@section('title', '编辑文章信息')
@section('content')
   <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
     <div class="panel panel-default">
       <div class="panel-heading">
         <h4>编辑文章信息</h4>
       </div>
       <div class="panel-body">
         @include('shared.errors')

         <form method="POST" action="{{ route('book.update', $book->id) }}" name="edit_book">
           {{ csrf_field() }}
              <div class="grayout">
                <h4>1. 请选择文章原创性</h4>
                <label class="hidden"><input type="radio" name="originalornot" value="1" {{ $book->original ? 'checked' : '' }} ></label>
                <label class="radio-inline"><input type="radio" disabled {{ $book->original ? 'checked' : '' }} >原创</label>
                <label class="hidden"><input type="radio" name="originalornot" value="0" {{ $book->original ? '' : 'checked' }} ></label>
                <label class="radio-inline"><input type="radio" disabled {{ $book->original ? '' : 'checked' }} >同人</label>
              </div>
              
              @include('books._book_input')
             <button type="submit" class="btn btn-danger sosad-button">确认修改</button>
         </form>
       </div>
     </div>
   </div>
@stop
