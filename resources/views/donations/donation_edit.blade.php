@extends('layouts.default')
@section('title', '兑换福利码')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">兑换福利码</div>
                <div class="panel-body">
                    @include('shared.errors')
                    <form method="POST" action="{{ route('donation.donation_update', $record->id) }}">
                            {{ csrf_field() }}
                            @method('PATCH')

                        <div class="checkbox">
                            <label><input type="checkbox" name="show_amount" {{ $record->show_amount ? 'checked' : '' }}>显示具体金额？</label>&nbsp;
                            <div class="form-group">
                                <input type="text" name="donation_amount" class="form-control" value="{{ $record->donation_amount}}" disabled>
                            </div>
                            <label><input type="checkbox" name="is_anonymous" {{ $record->is_anonymous ? 'checked' : '' }}>马甲？</label>&nbsp;
                            <div class="form-group">
                                <input type="text" name="donation_majia" class="form-control" value="{{ $record->donation_majia ?? '匿名咸鱼'}}">
                                <h6>(可以输入20个字内的马甲)</h6>
                            </div>
                        </div>
                        @if($info->donation_level>=4)
                        <div class="form-group">
                            <textarea name="donation_message" rows="3" class="form-control" id="markdowneditor" placeholder="输入您的赞助感言吧～">{{ $record->donation_message }}</textarea>
                        </div>
                        @endif

                        <button type="submit" class="btn btn-md btn-danger sosad-button">更新记录信息</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
