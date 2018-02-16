@extends('layouts.default')
@section('title', '第'.$homework->id.'次作业：发送通知')
@section('content')
<div class="container-fluid">
   <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
      <div class="panel panel-default">
         <div class="panel-heading"><h4>{{'第'.$homework->id.'次作业：发送通知'}}</h4></div>
         <div class="panel-body">
            <form method="POST" action="{{ route('homework.sendreminder', $homework->id) }}">
              {{ csrf_field() }}
                 <h4>请选择用户：</h4>
                 <div class="" id="students">
                    @foreach ($homework->registered as $student)
                       <input type="checkbox" class="students" name="students[]" value="{{ $student->id }}">{{ $student->name }}，
                    @endforeach
                 </div>
                 <br>
                 <div class="">
                    <label class="radio-inline"><input type="radio" name="check" value="1" onclick="checkAll('students')">全选</label>
                    <label class="radio-inline"><input type="radio" name="check" value="2" onclick="uncheckAll('students')">全不选</label>
                 </div>
                <div class="form-group">
                  <label for="body">消息正文：</label>
                  <textarea name="body" data-provide="markdown" id="messagetouser" rows="10" class="form-control" placeholder="消息">{{ old('body') }}</textarea>
                  <button type="button" onclick="retrievecache('messagetouser')" class="sosad-button-control addon-button">恢复数据</button>
                </div>
                <button type="submit" class="btn btn-primary">发布</button>
            </form>
         </div>
      </div>
   </div>
</div>
@stop
