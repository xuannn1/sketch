@switch($type)
@case('post')
<div class="font-4">
    <a href="{{ route('post.show', $model->id) }}">{{  $model->brief }}</a>
</div>
@break

@case('thread')
<div class="font-2">
    <a href="{{ route('thread.show', $model->id) }}">《{{ $model->title}}》</a>
</div>
<div class="font-4">
    {{ $model->brief}}
</div>

@break

@case('status')
<a href="{{ route('status.show', $model->id) }}">{{$model->brief}}</a>
@break

@case('quote')
<div class="font-4">
    {{$model->id}}号题头
</div>
<div class="font-2">
    <a href="{{ route('quote.show', $model->id) }}">
        {{ $model->body}}
    </a>
</div>


@break

@default
未完成
@endswitch
