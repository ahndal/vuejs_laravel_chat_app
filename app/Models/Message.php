<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $guarded = [];

    public function chats()
    {
        return $this->hasManyThrough(Chat::class);
    }

    public function createForSend($session_id)
    {
        $this->chats()->create([
            'session_id'=>$session_id,
            'type'=>0,
            'user_id'=>auth()->id()
        ]);
    }

    public function createForReceive($session_id, $to_user)
    {
        $this->chats()->create([
            'session_id'=>$session_id,
            'type'=>1,
            'user_id'=>$to_user
        ]);
    }
}
