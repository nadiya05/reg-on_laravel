<?php

namespace App\Events;

use App\Models\Chat;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\ShouldBroadcast;

class MessageSent implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $chat;

    public function __construct(Chat $chat)
    {
        $this->chat = $chat;
    }

    public function broadcastOn()
    {
        // CHANNEL HARUS PERSIS DENGAN YANG DI FLUTTER
        return new Channel('chat.' . $this->chat->user_id);
    }

    public function broadcastWith()
{
    return [
        'id'        => $this->message->id,
        'user_id'   => $this->message->user_id,
        'sender'    => $this->message->sender,
        'message'   => $this->message->message,
        'avatar'    => $this->message->sender === 'user'
                        ? $this->message->user->foto_url
                        : ($this->message->admin->foto_url ?? asset('storage/images/default-avatar.png')),
        'created_at'=> $this->message->created_at->toISOString(),
    ];
}
}
