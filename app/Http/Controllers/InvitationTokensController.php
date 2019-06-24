<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvitationToken;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Auth;


class InvitationTokensController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $invitation_tokens = InvitationToken::orderBy('created_at', 'desc')
        ->paginate(config('constants.index_per_page'));
        return view('admin.invitation_token_index', compact('invitation_tokens'));
    }

    public function create()
    {
        return view('admin.invitation_token_create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'token' => 'required|string|alpha_dash|max:50|unique:invitation_tokens',
            'eligible-days' => 'required|numeric',
            'eligible-hours' => 'required|numeric',
            'invitation_times' => 'required|numeric',
            'refresh_times' => 'required|numeric',
        ]);

        $new_token = [];
        $new_token['token'] = $request->token;
        $new_token['invite_until'] = Carbon::now()->addDays(request('eligible-days'))->addHours(request('eligible-hours'))->toDateTimeString();
        $new_token['invitation_times'] = $request->invitation_times;
        $new_token['refresh_times'] = $request->refresh_times;
        $new_token['user_id'] = Auth::id();
        InvitationToken::create($new_token);
        return redirect()->route('invitation_tokens.index');
    }
}
