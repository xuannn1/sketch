
@if(Auth::user()->no_posting)
<div class="">
<h6 class="text-center">你被禁言，暂时不能发动态。</h6>
</div>
@elseif(Auth::user()->level < 4)
<div class="">
<h6 class="text-center greyout">你的等级低于4，暂时不能发动态。</h6>
</div>
@else
<div class="panel-heading">
    @include('shared.errors')
    <form action="{{ route('status.store') }}" method="POST">
        {{ csrf_field() }}
        <div class="container-fluid">
            <h6 style="color:#d66666">（动态公开可见，请勿涉及“边限”内容。）</h6>
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
