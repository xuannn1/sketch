@foreach($records as $record)
<div class="">
    <h5>
	   <a href="{{ route('user.show', $record->user_id) }}">{{ $record->name }}</a>
	   &nbsp;
	   {{ $record->created_at }}
	   &nbsp;
	   {{ $admin_operation[$record->operation] }}
	    {!! Helper::trimtext($record->thread_title.$record->post_body.$record->postcomment_body.$record->operated_users_name,20) !!}
	   &nbsp;
	   原因：{{ $record->reason }}
	</h5>
	<hr class="narrow">
</div>
@endforeach
