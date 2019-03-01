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

    public function store(User $user, StoreMessage $form)
    {
        $message = $form->generateMessage();
        return response()->success($message);
    }

    public function index(User $user, Request $request)
    {
        if (auth('api')->id() === $user->id || auth('api')->user()->isAdmin()){//若访问的信箱为登录用户的信箱或登录用户为管理员
            $type = $request->withStyle;
            $chatWith = $request->chatWith;
            $query = Message::with('poster', 'receiver', 'body');

            switch ($type) {
              case 'sendbox': $query = $query->withPoster($user->id);
              break;
              case 'dialogue': if($chatWith) {
                  $query = $query->withDialogue($user->id, $chatWith);
              }else {
                  abort(422);
              }
              break;
              default: $query = $query->withReceiver($user->id)->withRead($request->read);
              break;
            }
            $messages = $query->withOrdered($request->ordered)->paginate(config('constants.messages_per_page'));
            return response()->success([
                $type => MessageResource::collection($messages),
                'paginate' => new PaginateResource($messages),
            ]);
        }
    }
}
