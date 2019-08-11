@foreach (['danger', 'warning', 'success', 'info'] as $msg)
@if (Session::has($msg))
<div class="flash-message">
    <p class="alert alert-{{ $msg }}">
        {!! session()->get($msg)!!}
    </p>
</div>
@endif
@endforeach
<div class="hidden alert" id="ajax-message"></div>
