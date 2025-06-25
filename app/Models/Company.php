<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'companies'; // Đặt tên bảng
    protected $fillable = ['name', 'desc', 'id_user_main', 'logo', 'status'];
}
