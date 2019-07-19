@foreach($tags as $tag)
<a class="btn  btn-md btn-primary sosad-button-control" href="{{route('books.index', ['withTag'=>$tag->id])}}">{{$tag->tag_name}}</a>
@endforeach
