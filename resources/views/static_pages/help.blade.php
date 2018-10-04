@extends('layouts.default')
@section('title', '帮助')
@section('content')
<div class="container">
   <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
      <div class="panel-group">
         <div class="panel panel-default">
            <div class="panel-heading">
               <div class="container-fluid">
                  <div class="row">
                     <div class="col-xs-10 col-xs-offset-1">
                        <h1>帮助</h1>
                     </div>
                  </div>
               </div>
            </div>
            <div class="panel-body">
               <div class="container-fluid">
                  <div class="row">
                     <div class="col-xs-10 col-xs-offset-1 h6">
                       <?php $helps = $data['webinfo_help'];  ?>
                       @foreach ($helps as $i=>$help)
                        <div class="row">
                          <a data-toggle="collapse" data-target="#part{{$i}}" class="h4">{{$help[0]}}</a>
                          <div id="part{{$i}}" class="collapse h6 col-xs-offset-1">
                            {!! Helper::sosadMarkdown($help[1]) !!}
                          </div>
                        </div>
                       @endforeach
                        <br>
                       <a data-toggle="collapse" data-target="#settings" class="h4">当前数据</a>
                       <div id="settings" class="collapse h6 col-xs-offset-1">
                         <p>信息每页显示：{{ $data['items_per_page'] }}个</p>
                         <p>信息每分区显示：{{ $data['items_per_part'] }}个</p>
                         <p>目录每页显示：{{ $data['index_per_page'] }}个</p>
                         <p>目录每分区显示：{{ $data['index_per_part'] }}个</p>
                         <p>长评标准：{{ $data['longcomment_lenth'] }}字</p>
                         <p>新章节的更新字数必须达到{{ $data['update_min'] }}字，才能计入本书“最后更新”的排名数据（顶帖）</p>
                       </div>

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
