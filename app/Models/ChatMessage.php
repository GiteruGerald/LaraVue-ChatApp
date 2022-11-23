<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    public function room()
    {
        $this->hasOne(ChatRoom::class, 'foreign_key', 'chat_room_id');
    }

    public function user()
    {
        $this->hasOne(User::class, 'foreign_key', 'user_id');
    }
}