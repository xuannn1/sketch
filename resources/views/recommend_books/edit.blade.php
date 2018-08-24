@extends('layouts.default')
@section('title', '修改推荐语')
@section('content')
<div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>修改推荐语</h4>
        </div>
        <div class="panel-body">
            @include('shared.errors')

            <form method="POST" action="{{ route('recommend_books.update', $recommend_book) }}">
                {{ csrf_field() }}

                <div class="form-group">
                    <div class="">
                        <h5>推荐书籍:
                            <a href="{{ route('thread.show', $recommend_book->thread) }}">{{ $recommend_book->title }}</a>
                        </h5>
                    </div>

                    <label for="recommendation">推荐语：</label>
                    <textarea name="recommendation" rows="2" class="form-control" required>{{ $recommend_book->recommendation }}</textarea>
                </div>

            <button type="submit" class="btn btn-danger sosad-button">修改</button>
        </form>
    </div>
</div>
</div>

@stop
