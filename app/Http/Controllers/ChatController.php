<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Session;

class ChatController extends Controller
{
    public function send(Session $session, Request $request)
    {
        $message = $session->message()->create(['content' => $request->content]);

        $message->createForSend($session->id);

        $message->createForReceive($session->id, $request->to_user);

        return response($message, 200);
    }
}
