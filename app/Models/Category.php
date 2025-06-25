<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories'; // Đặt tên bảng
    protected $fillable = ['name'];

    public function posts()
    {
        return $this->hasMany(Post::class, 'job_category');
    }
    public function keyWord() {
        return $this->hasMany(SearchSuggestion::class, 'category_id');
    }
}
