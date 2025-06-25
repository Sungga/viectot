<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $table = 'candidates'; // Đặt tên bảng
    protected $fillable = [
        'id_user',     // ID của người dùng (bắt buộc)
        'name',        // Tên ứng viên (có thể null)
        'avatar',      // avt
        'province',    // ID tỉnh/thành phố (có thể null)
        'district',    // ID quận/huyện (có thể null)
        'birthdate',   // Ngày sinh (có thể null)
        'sex',         // Giới tính (có thể null)
        'phone',       // Số điện thoại (có thể null)
        'id_category', // ID ngành nghề (có thể null)
        'cv_limit', // Giới hạn lưu trữ cv
    ];

    public function account() {
        return $this->belongsTo(Account::class, 'id_user'); 
    }
}
