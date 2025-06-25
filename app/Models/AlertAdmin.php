<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlertAdmin extends Model
{
    use HasFactory;

    protected $table = 'alert_admins';

    protected $fillable = [
        'reporter_name',
        'reporter_id',
        'content',
        'image_path',
        'post_id',
        'status',
    ];

    /**
     * Người báo cáo
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Bài viết bị báo cáo
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
