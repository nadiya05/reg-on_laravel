<?php
namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use App\Events\MessageSent;

class KelolaChatController extends Controller
{
    // List user + default chat
    public function index(Request $request)
    {
        $chatSearch = $request->input('search');

        $users = User::whereHas('chats')
            ->with(['chats' => fn($q) => $q->latest()->limit(1)])
            ->when($chatSearch, function($q) use ($chatSearch) {
                $q->where('name', 'like', "%{$chatSearch}%")
                ->orWhereHas('chats', fn($chat) =>
                    $chat->where('message', 'like', "%{$chatSearch}%")
                );
            })
            ->get()
            ->sortByDesc(fn($u) => optional($u->chats->first())->created_at)
            ->values();

        $activeUser = $users->first();

        // simpan last_open jika ada active user
        if ($activeUser) {
            session()->put("chat_last_open_{$activeUser->id}", now());
        }

        $messages = $activeUser
            ? Chat::with('user')->where('user_id', $activeUser->id)->orderBy('created_at', 'asc')->get()
            : collect();

        return view('chat.index', compact('users','activeUser','messages','chatSearch'));
    }

    // Show chat user spesifik
    public function show(Request $request, $user_id)
    {
        $chatSearch = $request->input('search');
        $activeUser = User::findOrFail($user_id);

        // UPDATE last_open untuk user yang diklik admin
        session()->put("chat_last_open_{$user_id}", now());

        $messages = Chat::with('user')
            ->where('user_id', $user_id)
            ->when($chatSearch, fn($q) => $q->where('message','like',"%{$chatSearch}%")
                ->orWhere('sender','like',"%{$chatSearch}%"))
            ->orderBy('created_at','asc')
            ->get();

        $users = User::whereHas('chats')->orderBy('name')->get();

        return view('chat.index', compact('users','activeUser','messages','chatSearch'));
    }

    // Kirim pesan admin
    public function sendReply(Request $request, $user_id)
    {
        $request->validate(['message' => 'required|string']);

        $chat = Chat::create([
            'user_id' => $user_id,
            'sender'  => 'admin',
            'message' => $request->message,
            'meta'    => ['admin_id' => auth()->id()]
        ]);

        event(new MessageSent($chat));

        return back();
    }

    // Hapus pesan
    public function destroy($chat_id)
    {
        Chat::findOrFail($chat_id)->delete();
        return back();
    }

    // Hapus semua chat user
    public function clearUserChat($user_id)
    {
        Chat::where('user_id',$user_id)->delete();
        return back();
    }
}
