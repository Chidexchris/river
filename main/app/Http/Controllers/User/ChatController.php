<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function messages(Request $request)
    {
        $userId = auth('web')->id();
        $after = $request->integer('after', 0);

        $query = ChatMessage::where('user_id', $userId)->where('id', '>', $after);
        $messages = $after === 0
            ? $query->latest('id')->limit(100)->get()->sortBy('id')->values()
            : $query->oldest('id')->limit(100)->get();

        return response()->json([
            'messages' => $messages
                ->map(fn(ChatMessage $message) => [
                    'id' => $message->id,
                    'from_admin' => $message->isFromAdmin(),
                    'message' => $message->message,
                    'time' => $message->created_at->format('H:i'),
                ]),
        ]);
    }

    public function store(Request $request)
    {
        $request->merge(['message' => trim((string) $request->input('message'))]);

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $message = ChatMessage::create([
            'user_id' => auth('web')->id(),
            'message' => trim($validated['message']),
        ]);

        return response()->json([
            'message' => [
                'id' => $message->id,
                'from_admin' => false,
                'message' => $message->message,
                'time' => $message->created_at->format('H:i'),
            ],
        ], 201);
    }
}
