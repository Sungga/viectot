<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    protected $table = 'alerts'; // Đặt tên bảng
    
    protected $fillable = [
        'title',
        'content',
        'type',
        'id_user',
        'status',
        'id_branch',
        'id_post',
    ];
}
