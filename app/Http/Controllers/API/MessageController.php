<?php

namespace App\Http\Controllers\API;

use App\Events\GotMessage;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class MessageController extends Controller
{
    public function index(): JsonResponse
    {
        $messages = Message::with('user:id,name')->get();

        return response()->json($messages);
    }


    public function store(Request $request): JsonResponse
    {
        // dd($request->all(), auth()->id());

        $message = Message::create([
            'user_id' => auth()->id(),
            'text' => $request->get('text'),
        ]);
        GotMessage::dispatch([
            'id' => $message->id,
            'user_id' => $message->user_id,
            'text' => $message->text,
            'time' => $message->created_at,
            'user_name' => $message->user?->name,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Message created and job dispatched.",
        ]);
        
    }
}
