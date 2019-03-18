<?php

namespace App\Http\Controllers\API;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Notification;
use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\PaginateResource;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(User $user)
    {
        if ((auth('api')->id() != $user->id)&&(!auth('api')->user()->isAdmin())){
            abort(403);
        }

        $notifications = Notification::with('notifiable');
        ->withOrdered($request->ordered)
        ->withRead($request->read)
        ->paginate(config('constants.messages_per_page'));

        return response()->success([
            'notifications' => NotificationResource::collection($notifications),
            'paginate' => new PaginateResource($notifications),
        ]);

    }

}
