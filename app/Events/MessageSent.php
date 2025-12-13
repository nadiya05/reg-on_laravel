<?php

namespace App\Events;

use App\Models\Chat;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageSent implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $chat;

    public function __construct(Chat $chat)
    {
        $this->chat = $chat;
    }

    // Broadcast ke private channel user (user_id)
    public function broadcastOn()
    {
        return new PrivateChannel('chat.' . $this->chat->user_id);
    }

    // Nama event (kamu bisa juga pakai default class name)
    public function broadcastAs()
    {
        return 'message.sent';
    }

    // Payload: pastikan key sama seperti API / Flutter expects
    public function broadcastWith()
    {
        return [
            'id' => $this->chat->id,
            'user_id' => $this->chat->user_id,
            'sender' => $this->chat->sender,
            'message' => $this->chat->message,
            'avatar' => $this->chat->sender === 'user'
                ? ($this->chat->user?->foto ? asset('storage/' . $this->chat->user->foto) : asset('storage/default/user.png'))
                : (method_exists($this->chat, 'adminUser') && $this->chat->adminUser()?->foto
                    ? asset('storage/' . $this->chat->adminUser()->foto)
                    : asset('storage/default/admin.png')),
            // match API key name
            'created_at' => $this->chat->created_at->timezone('Asia/Jakarta')->format('Y-m-d H:i'),
        ];
    }
}
