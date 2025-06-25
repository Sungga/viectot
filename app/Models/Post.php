<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts'; // Đặt tên bảng
    protected $fillable = [
        'title',
        'id_branch',
        'employment_type',
        'work_mode',
        'salary_type',
        'salary',
        'description',
        'skills',
        'gender',
        'experience',
        'degree',
        'quantity',
        'deadline',
        'job_category',
        'status',
    ];
    

    public function branch() {
        return $this->belongsTo(Company_branch::class, 'id_branch'); 
    }

    public function category_show() {
        return $this->belongsTo(Category::class, 'job_category');
    }
}
