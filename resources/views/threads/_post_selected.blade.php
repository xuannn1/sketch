<!-- 已经筛选的情况 -->
<div class="selected">
    @if(request()->withType&&array_key_exists(request()->withType,$selector['withType']))
    <a class="btn btn-info btn-md sosad-button-control" href="{{ route('thread.show',
    array_merge(['thread'=>$thread->id], request()->only('withComponent', 'withFolded', 'userOnly', 'withReplyTo', 'inComponent', 'ordered'))) }}" role="button">{{ $selector['withType'][request()->withType] }}<span class="glyphicon glyphicon-remove"></span></a>
    @endif
    @if(request()->withComponent&&array_key_exists(request()->withComponent, $selector['withComponent']))
    <a class="btn btn-info btn-md sosad-button-control" href="{{ route('thread.show',
    array_merge(['thread'=>$thread->id], request()->only('withType', 'withFolded', 'userOnly', 'withReplyTo', 'inComponent', 'ordered'))) }}" role="button">{{ $selector['withComponent'][request()->withComponent] }}<span class="glyphicon glyphicon-remove"></span></a>
    @endif
    @if(request()->withFolded&&array_key_exists(request()->withFolded,$selector['withFolded']))
    <a class="btn btn-info btn-md sosad-button-control" href="{{ route('thread.show',
    array_merge(['thread'=>$thread->id], request()->only('withType', 'withComponent', 'userOnly', 'withReplyTo', 'inComponent', 'ordered'))) }}" role="button">{{ $selector['withFolded'][request()->withFolded] }}<span class="glyphicon glyphicon-remove"></span></a>
    @endif
    @if(request()->userOnly)
    <a class="btn btn-info btn-md sosad-button-control" href="{{ route('thread.show',
    array_merge(['thread'=>$thread->id], request()->only('withType', 'withComponent', 'withFolded',  'withReplyTo', 'inComponent', 'ordered'))) }}" role="button">只看用户{{ request()->userOnly }}<span class="glyphicon glyphicon-remove"></span></a>
    @endif
    @if(request()->withReplyTo)
    <a class="btn btn-info btn-md sosad-button-control" href="{{ route('thread.show',
    array_merge(['thread'=>$thread->id], request()->only('withType', 'withComponent', 'withFolded', 'userOnly', 'inComponent', 'ordered'))) }}" role="button">只看对{{ request()->withReplyTo }}的回复<span class="glyphicon glyphicon-remove"></span></a>
    @endif
    @if(request()->inComponent)
    <a class="btn btn-info btn-md sosad-button-control" href="{{ route('thread.show',
    array_merge(['thread'=>$thread->id], request()->only('withType', 'withComponent', 'withFolded', 'userOnly', 'withReplyTo', 'ordered'))) }}" role="button">只看从{{ request()->inComponent }}发起的讨论串<span class="glyphicon glyphicon-remove"></span></a>
    @endif
    @if(request()->ordered&&array_key_exists(request()->ordered,$selector['ordered']))
    <a class="btn btn-info btn-md sosad-button-control" href="{{ route('thread.show',
    array_merge(['thread'=>$thread->id], request()->only('withType', 'withComponent', 'withFolded', 'userOnly', 'withReplyTo', 'inComponent'))) }}" role="button">{{ $selector['ordered'][request()->ordered] }}<span class="glyphicon glyphicon-remove"></span></a>
    @endif
</div>
<br>
