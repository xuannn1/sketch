<!-- 已经筛选的情况 -->
<div class="">
    @if(request()->withType)
    <a class="btn btn-info btn-md sosad-button-control" href="{{ route('thread.show',
    array_merge(['thread'=>$thread->id], request()->only('withComponent', 'userOnly', 'withReplyTo', 'ordered'))) }}" role="button">{{ config('selectors.post_filter.withType')[request()->withType] }}<span class="glyphicon glyphicon-remove"></span></a>
    @endif
    @if(request()->withComponent)
    <a class="btn btn-info btn-md sosad-button-control" href="{{ route('thread.show',
    array_merge(['thread'=>$thread->id], request()->only('withType', 'userOnly', 'withReplyTo', 'ordered'))) }}" role="button">{{ config('selectors.post_filter.withComponent')[request()->withComponent] }}<span class="glyphicon glyphicon-remove"></span></a>
    @endif
    @if(request()->userOnly)
    <a class="btn btn-info btn-md sosad-button-control" href="{{ route('thread.show',
    array_merge(['thread'=>$thread->id], request()->only('withType', 'withComponent', 'withReplyTo', 'ordered'))) }}" role="button">只看用户{{ request()->userOnly }}<span class="glyphicon glyphicon-remove"></span></a>
    @endif
    @if(request()->withReplyTo)
    <a class="btn btn-info btn-md sosad-button-control" href="{{ route('thread.show',
    array_merge(['thread'=>$thread->id], request()->only('withType', 'withComponent', 'userOnly', 'ordered'))) }}" role="button">只看对{{ request()->withReplyTo }}的回复<span class="glyphicon glyphicon-remove"></span></a>
    @endif
    @if(request()->ordered)
    <a class="btn btn-info btn-md sosad-button-control" href="{{ route('thread.show',
    array_merge(['thread'=>$thread->id], request()->only('withType', 'withComponent', 'userOnly', 'withReplyTo'))) }}" role="button">{{ config('selectors.post_filter.ordered')[request()->ordered] }}<span class="glyphicon glyphicon-remove"></span></a>
    @endif
</div>
<br>
