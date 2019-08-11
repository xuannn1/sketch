@extends('layouts.default')
@section('title', '提交Patreon赞助者信息')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">提交Patreon赞助者信息</div>
                <div class="panel-body">
                    @include('shared.errors')
                    <form action="{{ route('donation.patreon_store') }}" method="POST">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="form-group">
                            <label class="col-md-4 control-label">Patreon账户邮箱地址：</label>
                            <h6 class="grayout">(信息提交后，工作人员会关联Patreon赞助信息，大概需要1-3个工作日。)</h6>
                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                            </div>

                        </div>
                        <button type="submit" class="btn btn-lg btn-danger sosad-button">
                            提交信息
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
