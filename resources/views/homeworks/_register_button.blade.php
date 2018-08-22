<div class="main-text">
    @if($thread->homework->registration_on)
        本次作业报名所需丧点为：{{ $thread->homework->hold_sangdian }}<br>
        @if($thread->homework->register_number>0)
            第一波报名开始：{{ Carbon\Carbon::parse($thread->homework->register_at)->diffForHumans() }}<br>
            第一波报名名额剩余：{{ $thread->homework->register_number }}<br>
        @endif
        @if($thread->homework->register_number_b>0)
            第二波报名开始：{{ Carbon\Carbon::parse($thread->homework->register_at_b)->diffForHumans() }}<br>
            第二波报名名额剩余：{{ $thread->homework->register_number_b }}<br>
        @endif
        @if(Auth::check())
            @if((($thread->homework->register_at < Carbon\Carbon::now())&&($thread->homework->register_number>0))||(($thread->homework->register_at_b < Carbon\Carbon::now())&&($thread->homework->register_number_b>0)))
            <div class="text-center post-body">
                <a href="#" data-toggle="modal" data-target="#TriggerRegister" class="btn-md sosad-button-post">我要报名</a>
            </div>
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
                                <button type="submit" class="sosad-button-post btn-sm">确认报名</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        @else
        <h6 class="display-4">请 <a href="{{ route('login') }}">登录</a> 后参与报名</h6>
        @endif
    @else
    <p>本次作业报名已结束</p>
    @endif
</div>
