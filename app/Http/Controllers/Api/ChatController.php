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
            return response()->json(['data' => []], 200);
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
                        ? ($user->foto ? asset('storage/' . $user->foto) : asset('storage/default/user.png'))
                        : ($chat->adminUser() && $chat->adminUser()->foto
                            ? asset('storage/' . $chat->adminUser()->foto)
                            : asset('storage/default/admin.png')),

                    // Format TANPA DETIK
                    'created_at' => $chat->created_at
                        ->timezone('Asia/Jakarta')
                        ->format('Y-m-d H:i'),
                ];
            });

        return response()->json(['data' => $messages], 200);
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

        event(new MessageSent($chat));

        return response()->json([
            'data' => [
                'id'         => $chat->id,
                'user_id'    => $chat->user_id,
                'sender'     => $chat->sender,
                'message'    => $chat->message,
                'avatar' => $user->foto
                    ? asset('storage/' . $user->foto)
                    : asset('storage/default/user.png'),
                'created_at' => $chat->created_at
                    ->timezone('Asia/Jakarta')
                    ->format('Y-m-d H:i'),
            ]
        ], 201);
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
            'meta'    => ['admin_id' => $admin->id] // optional
        ]);

        event(new MessageSent($chat));

        return response()->json([
            'data' => [
                'id'         => $chat->id,
                'user_id'    => $chat->user_id,
                'sender'     => $chat->sender,
                'message'    => $chat->message,
                'avatar' => $admin->foto
                    ? asset('storage/' . $admin->foto)
                    : asset('storage/default/admin.png'),
                'created_at' => $chat->created_at
                    ->timezone('Asia/Jakarta')
                    ->format('Y-m-d H:i'),
            ]
        ], 201);
    }

    // ================================
// START CHAT — SAPAAN OTOMATIS SETIAP HARI
//  - Sapaan dibuat 1x per hari
//  - Hanya dari penduduk (bukan admin)
// ================================
public function startChat(Request $request)
{
    if (!Auth::check()) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $user = Auth::user();

    // Cek apakah sudah ada sapaan HARI INI
    $today = now()->timezone('Asia/Jakarta')->format('Y-m-d');

    $alreadyToday = Chat::where('user_id', $user->id)
        ->where('sender', 'user')
        ->whereDate('created_at', $today)
        ->exists();

    if ($alreadyToday) {
        return response()->json(['message' => 'Sudah ada sapaan hari ini'], 200);
    }

    // Buat sapaan otomatis harian
    $chat = Chat::create([
        'user_id' => $user->id,
        'sender'  => 'user',
        'message' => "Halo MinLoh! Saya butuh bantuan.",
    ]);

    event(new MessageSent($chat));

    return response()->json([
        'data' => [
            'id'         => $chat->id,
            'user_id'    => $chat->user_id,
            'sender'     => $chat->sender,
            'message'    => $chat->message,
            'avatar'     => $user->foto ? asset('storage/' . $user->foto) : asset('storage/default/user.png'),
            'created_at' => $chat->created_at->timezone('Asia/Jakarta')->format('Y-m-d H:i'),
        ]
    ], 201);
}

    // ================================
    // GET PROFILE USER + ADMIN
    // ================================
    public function profile()
    {
        if (!Auth::check()) {
            return response()->json(null, 401);
        }

        $user = Auth::user();

        // Ambil admin pertama
        $admin = \App\Models\User::where('role', 'admin')->first();

        return response()->json([
            'user_id' => $user->id,
            'name' => $user->name,
            'avatar' => $user->foto
                ? asset('storage/' . $user->foto)
                : asset('storage/default/user.png'),

            'admin_name' => $admin?->name ?? 'Admin',
            'admin_avatar' => $admin && $admin->foto
                ? asset('storage/' . $admin->foto)
                : asset('storage/default/admin.png'),
        ], 200);
    }
}