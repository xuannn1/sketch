@extends('layouts.default')
@section('title', '注册')
@section('content')
<div class="container-fluid">
    <div class="col-sm-offset-3 col-sm-6">
        <div class="panel panel-default text-center">
            <div class="panel-body text-center">
                <h1><strong>Hi，你好&nbsp;</strong></h1>
            </div>
            <div class="panel-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class=""><strong>
                            {!! StringProcess::wrapParagraphs(ConstantObjects::system_variable()->register_slogan) !!}
                        </strong></div>
                    </div>
                </div>
            </div>
            <a href="{{route('register_by_invitation_form')}}" class="btn btn-md btn-primary sosad-button">邀请注册</a>
        </div>
    </div>
</div>
@stop
