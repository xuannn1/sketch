<form action="{{ route('statuses.store') }}" method="POST">
  {{ csrf_field() }}
  <div class="container-fluid">
     <div class="col-xs-10 col-sm-11">
       <textarea class="form-control" id="markdowneditor" rows="1" placeholder="今天你丧了吗…"  name="content">{{ old('content') }}</textarea>
     </div>
     <div class="col-xs-2 col-sm-1 text-right">
         <button type="submit" class="sosad-button btn btn-xs btn-primary">发布</button>
     </div>
  </div>
</form>
