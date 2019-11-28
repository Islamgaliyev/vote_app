<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Message;
use App\Events\MessageSendEvent;

class ChatController extends Controller
{

    public function __construct() {
        $this->middleware('auth');
    }


    public function index() {
        return view('chats');
    }

    public function getMessages() {
        return Message::with('user')->get();
    }

    public function sendMessage(Request $req) {
        try {
            $message = auth()->user()->messages()->create([
                'message' => $req->message
            ]);
            
            broadcast(new MessageSendEvent($message->load('user')))->toOthers();
            return response()->json(['success'=>true]);
        } catch (\Excpetion $e) {
            return response()->json(['success'=>false, 'message'=>$e->getMessage()]);
        }
    }
}
