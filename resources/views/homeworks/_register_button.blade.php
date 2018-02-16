<div class="text-center">
   @if($homework->registration_on)
   @if(Auth::check())
   <a href="#" data-toggle="modal" data-target="#TriggerRegister" class="btn btn-md btn-info sosad-button">我要报名</a>
   <div class="modal fade" id="TriggerRegister" role="dialog">
       <div class="modal-dialog">
         <div class="modal-content">
            <form action="{{ route('homework.register', $thread->homework_id) }}" method="POST">
               {{ csrf_field() }}
               <div class="form-group">
                  <label>请输入您的报名马甲：</label>
                  <input type="text" name="majia" class="form-control" value="{{ Auth::user()->name}}">
               </div>
               <div class="">
                  <button type="submit" class="btn btn-primary sosad-button btn-sm">确认报名</button>
               </div>
            </form>
         </div>
      </div>
   </div>
   @else
   <h6 class="display-4">请 <a href="{{ route('login') }}">登录</a> 后参与报名</h6>
   @endif
   @else
   <p>本次作业报名已结束</p>
   @endif
</div>
