<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SearchSuggestion extends Model
{
    protected $table = 'search_suggestions'; // Đặt tên bảng
    protected $fillable = ['category_id', 'keyword'];
}
