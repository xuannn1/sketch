<!-- 已经筛选的情况 -->
<div class="selected">
    @if(request()->withType)
    <a class="btn btn-info btn-md sosad-button-control" href="{{ route('thread.show',
    array_merge(['thread'=>$thread->id], request()->only('withComponent', 'withFolded', 'userOnly', 'withReplyTo', 'ordered'))) }}" role="button">{{ $selector['withType'][request()->withType] }}<span class="glyphicon glyphicon-remove"></span></a>
    @endif
    @if(request()->withComponent)
    <a class="btn btn-info btn-md sosad-button-control" href="{{ route('thread.show',
    array_merge(['thread'=>$thread->id], request()->only('withType', 'userOnly', 'withReplyTo', 'ordered'))) }}" role="button">{{ $selector['withComponent'][request()->withComponent] }}<span class="glyphicon glyphicon-remove"></span></a>
    @endif
    @if(request()->withFolded)
    <a class="btn btn-info btn-md sosad-button-control" href="{{ route('thread.show',
    array_merge(['thread'=>$thread->id], request()->only('withType', 'withComponent', 'userOnly', 'withReplyTo', 'ordered'))) }}" role="button">{{ $selector['withComponent'][request()->withComponent] }}<span class="glyphicon glyphicon-remove"></span></a>
    @endif
    @if(request()->userOnly)
    <a class="btn btn-info btn-md sosad-button-control" href="{{ route('thread.show',
    array_merge(['thread'=>$thread->id], request()->only('withType', 'withComponent', 'withFolded',  'withReplyTo', 'ordered'))) }}" role="button">只看用户{{ request()->userOnly }}<span class="glyphicon glyphicon-remove"></span></a>
    @endif
    @if(request()->withReplyTo)
    <a class="btn btn-info btn-md sosad-button-control" href="{{ route('thread.show',
    array_merge(['thread'=>$thread->id], request()->only('withType', 'withComponent', 'withFolded', 'userOnly', 'ordered'))) }}" role="button">只看对{{ request()->withReplyTo }}的回复<span class="glyphicon glyphicon-remove"></span></a>
    @endif
    @if(request()->ordered)
    <a class="btn btn-info btn-md sosad-button-control" href="{{ route('thread.show',
    array_merge(['thread'=>$thread->id], request()->only('withType', 'withComponent', 'withFolded', 'userOnly', 'withReplyTo'))) }}" role="button">{{ $selector['ordered'][request()->ordered] }}<span class="glyphicon glyphicon-remove"></span></a>
    @endif
</div>
<br>
