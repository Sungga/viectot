<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company_branch extends Model
{
    protected $table = 'company_branches'; // Đặt tên bảng
    protected $fillable = ['id_company', 'name', 'id_user_manager', 'desc', 'province', 'district', 'latitude', 'longitude'];

    public function company() {
        return $this->belongsTo(Company::class, 'id_company');
    }

    public function images() {
        return $this->hasMany(Branch_img::class, 'id_branch');
    }

    public function branchProvince() {
        return $this->belongsTo(Province::class, 'province'); // province là khóa ngoại
    }

    public function branchDistrict() {
        return $this->belongsTo(District::class, 'district'); // district là khóa ngoại
    }

    public function post() {
        return $this->hasMany(Post::class, 'id_branch');
    }
}
