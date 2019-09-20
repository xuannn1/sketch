@extends('layouts.default')
@section('title', '兑换福利码')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">兑换福利码</div>
                <div class="panel-body">
                    @include('shared.errors')
                    <form action="{{ route('donation.redeem_token') }}" method="POST">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="row">
                            <div class="form-group">
                                <label class="control-label">福利码token：</label>
                                <input type="text" class="form-control" name="token" value="{{ old('token') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <div class="captcha">
                                    <span>{!! captcha_img() !!}</span>
                                    <button type="button" class="btn btn-success"><i class="fa fa-refresh" id="refresh"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <input id="captcha" type="text" class="form-control" placeholder="输入验证码" name="captcha">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-lg btn-danger sosad-button">
                            兑换福利码
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
