<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company_document extends Model
{
    protected $table = 'company_documents'; // Đặt tên bảng
    protected $fillable = ['id_company', 'type', 'file_path'];
}
