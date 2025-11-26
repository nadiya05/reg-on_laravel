<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chat;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // ================================
    // GET CHAT USER (LIST PESAN)
    // ================================
    public function index()
    {
        if (!Auth::check()) {
            return response()->json(['data' => []]);
        }

        $user = Auth::user();

        $messages = Chat::where('user_id', $user->id)
            ->orderBy('created_at')
            ->get()
            ->map(function ($chat) use ($user) {
                return [
                    'id'         => $chat->id,
                    'user_id'    => $chat->user_id,
                    'sender'     => $chat->sender,
                    'message'    => $chat->message,

                    // Avatar
                    'avatar'     => $chat->sender === 'user'
                        ? ($user->foto_url ? asset('storage/' . $user->foto_url) : asset('storage/default/user.png'))
                        : ($chat->admin && $chat->admin->foto_url
                            ? asset('storage/' . $chat->admin->foto_url)
                            : asset('storage/default/admin.png')),

                    // Format TANPA DETIK
                    'created_at' => $chat->created_at
                        ->timezone('Asia/Jakarta')
                        ->format('Y-m-d H:i'),
                ];
            });

        return response()->json(['data' => $messages]);
    }

    // ================================
    // USER KIRIM PESAN
    // ================================
    public function send(Request $request)
    {
        $request->validate(['message' => 'required|string']);
        $user = Auth::user();

        $chat = Chat::create([
            'user_id' => $user->id,
            'sender'  => 'user',
            'message' => $request->message,
        ]);

        broadcast(new MessageSent($chat))->toOthers();

        return response()->json([
            'data' => [
                'id'         => $chat->id,
                'user_id'    => $chat->user_id,
                'sender'     => $chat->sender,
                'message'    => $chat->message,
                'avatar'     => $user->foto_url
                    ? asset('storage/' . $user->foto_url)
                    : asset('storage/default/user.png'),
                'created_at' => $chat->created_at
                    ->timezone('Asia/Jakarta')
                    ->format('Y-m-d H:i'),
            ]
        ]);
    }

    // ================================
    // ADMIN MEMBALAS CHAT USER
    // ================================
    public function adminSend(Request $request, $userId)
    {
        $admin = Auth::user();

        if (!$admin->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate(['message' => 'required|string']);

        $chat = Chat::create([
            'user_id' => $userId,
            'sender'  => 'admin',
            'message' => $request->message,
        ]);

        broadcast(new MessageSent($chat))->toOthers();

        return response()->json([
            'data' => [
                'id'         => $chat->id,
                'user_id'    => $chat->user_id,
                'sender'     => $chat->sender,
                'message'    => $chat->message,
                'avatar'     => $admin->foto_url
                    ? asset('storage/' . $admin->foto_url)
                    : asset('storage/default/admin.png'),
                'created_at' => $chat->created_at
                    ->timezone('Asia/Jakarta')
                    ->format('Y-m-d H:i'),
            ]
        ]);
    }

    // ================================
    // START CHAT — HANYA SEKALI
    // ================================
    public function startChat(Request $request)
    {
        $user = Auth::user();

        $alreadyChat = Chat::where('user_id', $user->id)->exists();

        if ($alreadyChat) {
            return response()->json(['message' => 'Chat sudah tersedia'], 200);
        }

        $chat = Chat::create([
            'user_id' => $user->id,
            'sender'  => 'user',
            'message' => "Halo MinLoh, saya butuh bantuan."
        ]);

        broadcast(new MessageSent($chat))->toOthers();

        return response()->json([
            'data' => [
                'id'         => $chat->id,
                'user_id'    => $chat->user_id,
                'sender'     => $chat->sender,
                'message'    => $chat->message,
                'avatar'     => $user->foto_url
                    ? asset('storage/' . $user->foto_url)
                    : asset('storage/default/user.png'),
                'created_at' => $chat->created_at
                    ->timezone('Asia/Jakarta')
                    ->format('Y-m-d H:i'),
            ]
        ]);
    }
}
