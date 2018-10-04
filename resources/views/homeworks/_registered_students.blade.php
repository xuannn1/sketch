
<div class="main-text">
    已报名：<br>
    @if((Auth::check()&&(Auth::user()->admin)))
    @foreach($thread->homework->registered_students() as $i=>$student)
    {{$i+1}}. <span><a href="{{ route('user.show', $student->id) }}">{{ $student->name }}</a></span>{{ $student->majia ? '（'.$student->majia.'）':''}}<br>
    @endforeach
    @else
    @foreach($thread->homework->registered_students() as $i=>$student)
    {{$i+1}}. {{ $student->majia ?? $student->name }}<br>
    @endforeach
    @endif
    <br>
</div>
