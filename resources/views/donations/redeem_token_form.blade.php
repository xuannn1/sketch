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
                    <form action="{{ route('donation.redeem_token') }}" method="POST">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="form-group">
                            <label class="col-md-4 control-label">福利码token：</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="token" value="{{ old('token') }}">
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
