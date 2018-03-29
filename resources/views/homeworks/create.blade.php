@extends('layouts.default')
@section('title', '发布新作业')
@section('content')
   <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
     <div class="panel panel-default">
       <div class="panel-heading">
         <h4>发布新作业</h4>
       </div>
       <div class="panel-body">
         @include('shared.errors')

         <form method="POST" action="{{ route('homework.store') }}">
           {{ csrf_field() }}
             <div class="form-group">
               <label for="requirement">作业要求：</label>
               <textarea name="requirement" id="requirement" rows="12" class="form-control" data-provide="markdown" placeholder="作业时间-流程……etc">{{ old('requirement') }}</textarea>
               <button type="button" onclick="retrievecache('requirement')" class="sosad-button-control addon-button">恢复数据</button>
               <button href="#" type="button" onclick="wordscount('requirement');return false;" class="pull-right sosad-button-control addon-button">字数统计</button>
             </div>
             <!-- <div class="">
                <label><input type="checkbox" name="markdown" checked>使用Markdown语法？</label>
             </div> -->
             <button type="submit" class="btn btn-danger sosad-button">发布</button>
         </form>
       </div>
     </div>
   </div>

@stop
