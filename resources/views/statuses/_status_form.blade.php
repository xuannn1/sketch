<!-- <script type="text/javascript">
    function toggleS(show) {
        var ss = document.getElementById('status-submit').style;
        if (show) {ss.display = 'block';}
        else {ss.display = 'none';}
        // var s = document.querySelector('#status-submit');
        // s.classList.toggle('hidden');
    }
</script> -->

<form action="{{ route('statuses.store') }}" method="POST">
  {{ csrf_field() }}
  <div class="container-fluid status-wrapper">
     <div class="col-sm-11 col-xs-10 status-editor">
         <!-- <textarea class="form-control" rows="1" placeholder="今天你丧了吗…"  name="content" onblur="toggleS(false)" onfocus="toggleS(true)">{{ old('content') }}</textarea> -->
         <textarea class="form-control" rows="1" placeholder="今天你丧了吗…"  name="content">{{ old('content') }}</textarea>
     </div>
     <div class="col-sm-1 col-xs-2 status-submit">
         <button type="submit">
             <i class="fa fa-paper-plane"></i>
         </button>
     </div>
  </div>
</form>
