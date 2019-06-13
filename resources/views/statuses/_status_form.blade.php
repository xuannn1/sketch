
@if(Auth::user()->no_posting > Carbon\Carbon::now())
<div class="">
<h6 class="text-center">您被禁言至{{ Carbon\Carbon::parse(Auth::user()->no_posting)->diffForHumans() }}，暂时不能发动态。</h6>
</div>
@elseif(Auth::user()->user_level < 2)
<div class="">
<h6 class="text-center greyout">您的等级低于2，暂时不能发动态。</h6>
</div>
@else
<div class="panel-heading">
    @include('shared.errors')
    <form action="{{ route('statuses.store') }}" method="POST">
        {{ csrf_field() }}
        <div class="container-fluid">
            <div class="row">
                <textarea class="form-control" id="markdowneditor" rows="4" placeholder="今天你丧了吗…"  name="content">{{ old('content') }}</textarea>
            </div>
            <div >
                <button type="button" onclick="retrievecache('markdowneditor')" class="sosad-button-control addon-button">恢复数据</button>
                <button href="#" type="button" onclick="wordscount('markdowneditor');return false;" class="sosad-button-control addon-button">字数统计</button>
                <button type="submit" class="pull-right sosad-button btn btn-md btn-primary">发布</button>
                <span class="pull-right grayout"><small>状态字数限制180&nbsp;</small></span>
            </div>
        </div>
    </form>
</div>
@endif
