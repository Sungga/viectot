<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch_img extends Model
{
    protected $table = 'table_branch_imgs'; // Đặt tên bảng
    protected $fillable = ['id_branch', 'img'];

    // public function branch()
    // {
    //     return $this->belongsTo(Company_branch::class, 'id_branch');
    // }
}
