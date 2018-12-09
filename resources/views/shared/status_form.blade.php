<form action="#" method="POST">
    @include('shared.errors')
    {{ csrf_field() }}
    <textarea class="form-control" rows="3" placeholder="今天你丧了吗…" name="content">{{ old('content') }}</textarea>
    <button type="submit" class="btn btn-danger sosad-button pull-right">发布</button>
</form>
