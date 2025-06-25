<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $table = 'accounts'; // Đặt tên bảng
    protected $fillable = ['username', 'password', 'role', 'email', 'status', 'facebook_id'];
    protected $hidden = ['password']; // Ẩn mật khẩu khi trả về JSON
}
