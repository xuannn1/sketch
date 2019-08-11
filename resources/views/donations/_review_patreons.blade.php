@foreach ($patreons as $patreon)
<div class="panel panel-default">
    <div class="panel-heading">
        <div class="">
            <span class="badge bianyuan-tag badge-tag">{{$patreon->is_approved? '':'未通过'}}</span>
            <a href="{{ route('user.show', $patreon->user_id) }}">{{ $patreon->author->name }}</a>
            <span>{{$patreon->patreon_email}}</span>&nbsp;&nbsp;
            <a href="{{route('donation.approve_patreon', $patreon->id)}}" class="btn btn-xs btn-danger admin-button">通过关联</a>
        </div>
    </div>
    <div class="panel-body">
        <div class="">
            <?php $donation_records = $patreon->donation_records; ?>
            @include('donations._admin_donation_records')
        </div>
    </div>

</div>
@endforeach
