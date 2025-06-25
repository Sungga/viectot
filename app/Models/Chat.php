<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $table = 'chats'; // Đặt tên bảng
    protected $fillable = ['id_candidate', 'id_branch', 'id_post', 'message', 'sender'];

    public function post() {
        return $this->belongsTo(Account::class, 'id_post'); 
    }
}
