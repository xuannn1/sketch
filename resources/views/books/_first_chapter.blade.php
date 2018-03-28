@if((count($book->chapters)==1)&&($book->book_status == 2)&&($book->book_length ==1))
<?php $post = $book->chapters[0]->mainpost; ?>
    <div class="panel-body post-body">
       @include('posts._post_body')
    </div>
@endif
