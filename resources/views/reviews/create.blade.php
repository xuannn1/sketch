@extends('layouts.default')
@section('title', '添加推荐书籍')
@section('content')
<div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>添加推荐书籍</h4>
        </div>
        <div class="panel-body">
            @include('shared.errors')

            <form method="POST" action="{{ route('recommend_books.store') }}">
                {{ csrf_field() }}

                <div class="form-group">
                    <div class="">
                        <label>推荐书籍id:
                            <input type="text" name="thread_id" class="form-control" placeholder="请输入完整的推荐书籍id" required>
                        </label>
                    </div>

                    <label for="recommendation">推荐语：</label>
                    <textarea name="recommendation" rows="2" class="form-control" required>{{ old('recommendation') }}</textarea>

                    <div class="">
                        <label>是否为长评
                            <input type="radio" name="long" value="1"/>
                        </label>
                    </div>

                </div>

            <button type="submit" class="btn btn-danger sosad-button">添加</button>
        </form>
    </div>
</div>
</div>

@stop
