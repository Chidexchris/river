<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $threads = ChatMessage::with('user')->latest('id')->get()->groupBy('user_id');
        $selectedUser = $request->integer('user');

        if ($selectedUser && ! $threads->has($selectedUser)) {
            $selectedUser = null;
        }

        if (! $selectedUser) {
            $selectedUser = $threads->keys()->first();
        }

        $user = $selectedUser ? User::find($selectedUser) : null;
        $messages = $user
            ? ChatMessage::where('user_id', $user->id)->latest('id')->limit(100)->get()->sortBy('id')->values()
            : collect();
        $pageTitle = 'Customer Chat';

        return view('admin.chat.index', compact('pageTitle', 'threads', 'user', 'messages', 'selectedUser'));
    }

    public function messages(Request $request)
    {
        $userId = $request->integer('user');
        abort_unless($userId && User::whereKey($userId)->exists(), 404);

        return response()->json([
            'messages' => ChatMessage::where('user_id', $userId)
                ->where('id', '>', $request->integer('after', 0))
                ->oldest('id')
                ->limit(100)
                ->get(['id', 'admin_id', 'message', 'created_at'])
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
            'user' => ['required', 'integer', 'exists:users,id'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $message = ChatMessage::create([
            'user_id' => $validated['user'],
            'admin_id' => auth('admin')->id(),
            'message' => trim($validated['message']),
        ]);

        return response()->json([
            'message' => [
                'id' => $message->id,
                'from_admin' => true,
                'message' => $message->message,
                'time' => $message->created_at->format('H:i'),
            ],
        ], 201);
    }
}
