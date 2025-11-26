<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{userId}', function ($user, $userId) {
    // hanya user yang sama atau admin bisa subscribe
    // asumsi ada role check di user -> isAdmin()
    return (int) $user->id === (int) $userId || $user->isAdmin();
});
