@foreach (['danger', 'warning', 'success', 'info'] as $msg)
@if (Session::has($msg))
<div class="flash-message">
    <p class="alert alert-{{ $msg }}">
        {{ Session::get($msg)  }}
    </p>
</div>
@endif
@endforeach
