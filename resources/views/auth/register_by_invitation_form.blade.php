@extends('layouts.default')
@section('title', '邀请注册')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">邀请注册</div>
                <div class="panel-body">
                    @include('shared.errors')
                    <form action="{{ route('register_by_invitation') }}" method="POST">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group">
                            <label class="col-md-4 control-label">邀请码：</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="invitation_token" value="{{ old('invitation_token') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4"></div>
                            <div class="form-group col-md-4">
                                <div class="captcha">
                                    <span>{!! captcha_img() !!}</span>
                                    <button type="button" class="btn btn-success"><i class="fa fa-refresh" id="refresh"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                            </div>
                            <div class="form-group col-md-4">
                                <input id="captcha" type="text" class="form-control" placeholder="Enter Captcha" name="captcha">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-lg btn-danger sosad-button">
                            提交邀请码
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
