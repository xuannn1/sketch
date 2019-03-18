<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMessage;
use App\Models\User;
use App\Models\Message;
use App\Http\Resources\MessageResource;
use App\Http\Resources\PaginateResource;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function store(StoreMessage $form)
    {
        $message = $form->userSend();
        if(!$message){abort(403);}

        return response()->success([
            'message' => new MessageResource($message),
        ]);
    }

    public function sendMessages(StoreMessage $form)
    {
        $messages = $form->adminSend();
        if(!$messages){abort(403);}

        return response()->success([
            'messages' => MessageResource::collection($messages->load('body')),
        ]);
    }

    public function index(User $user, Request $request)
    {
        if (auth('api')->id() === $user->id
        || auth('api')->user()->isAdmin()){//若访问的信箱为登录用户的信箱或登录用户为管理员
            $chatWith = $request->chatWith ?? 0;
            $query = Message::with('poster.mainTitle', 'receiver.mainTitle', 'body');

            switch ($request->withStyle) {
                case 'sendbox': $query = $query->withPoster($user->id);
                break;
                case 'dialogue': $query = $query->withDialogue($user->id, $chatWith);
                break;
                default: $query = $query->withReceiver($user->id)->withRead($request->read);
                break;
            }
            $messages = $query->withOrdered($request->ordered)
            ->paginate(config('constants.messages_per_page'));
            if((request()->withStyle==='sendbox'
                || request()->withStyle==='dialogue')
                && (!auth('api')->user()->isAdmin())){
                $messages->except('seen');
            }
            return response()->success([
                'style' => $request->withStyle,
                'messages' => MessageResource::collection($messages),
                'paginate' => new PaginateResource($messages),
            ]);
        }else{
            abort(403);
        }
    }
}
