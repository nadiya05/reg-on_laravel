<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;

class KelolaChatController extends Controller
{
    public function index(Request $request)
    {
        $chatSearch = $request->input('search');

        // List user tanpa pagination + filter
        $users = User::whereHas('chats')
            ->when($chatSearch, function ($q) use ($chatSearch) {
                $q->where('name', 'like', "%{$chatSearch}%")
                  ->orWhereHas('chats', function ($chat) use ($chatSearch) {
                      $chat->where('message', 'like', "%{$chatSearch}%");
                  });
            })
            ->orderBy('name')
            ->get();

        return view('chat.index', [
            'users'      => $users,
            'chatSearch' => $chatSearch,
            'activeUser' => null,
            'messages'   => collect(),
        ]);
    }

    public function show(Request $request, $user_id)
    {
        $chatSearch = $request->input('search');
        $activeUser = User::findOrFail($user_id);

        // Ambil semua pesan user
        $messages = Chat::where('user_id', $user_id)
            ->when($chatSearch, function ($q) use ($chatSearch) {
                $q->where('message', 'like', "%{$chatSearch}%")
                  ->orWhere('sender', 'like', "%{$chatSearch}%");
            })
            ->orderBy('created_at')
            ->get();

        // Sidebar list user
        $users = User::whereHas('chats')->orderBy('name')->get();

        return view('chat.index', compact('users', 'activeUser', 'messages', 'chatSearch'));
    }

    public function sendReply(Request $request, $user_id)
    {
        $request->validate(['message' => 'required']);

        Chat::create([
            'user_id' => $user_id,
            'sender'  => 'admin',
            'message' => $request->message,
        ]);

        return back();
    }

    public function destroy($chat_id)
    {
        Chat::findOrFail($chat_id)->delete();
        return back();
    }

    public function clearUserChat($user_id)
    {
        Chat::where('user_id', $user_id)->delete();
        return back();
    }
}
