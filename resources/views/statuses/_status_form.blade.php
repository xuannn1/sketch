
@if(Auth::user()->no_posting)
<div class="">
<h6 class="text-center">您被禁言，暂时不能发动态。</h6>
</div>
@elseif(Auth::user()->level < 2)
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
                <textarea class="form-control" id="status_body" rows="4" placeholder="今天你丧了吗…"  name="status_body">{{ old('status_body') }}</textarea>
            </div>
            <div >
                <button type="button" onclick="retrievecache('status_body')" class="sosad-button-control addon-button">恢复数据</button>
                <button type="submit" class="pull-right sosad-button btn btn-md btn-primary">发布</button>
            </div>
        </div>
    </form>
</div>
@endif
