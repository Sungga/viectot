<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $table = 'applies';

    protected $fillable = [
        'profileSummary',
        'id_cv',
        'id_candidate',
        'id_post',
        'status',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class, 'id_candidate');
    }

    public function cv()
    {
        return $this->belongsTo(Cv::class, 'id_cv');
    }

    public function cvNotReject()
    {
        // return $this->belongsTo(Cv::class, 'id_cv')->where('status', '!=', 'reject');
        return $this->belongsTo(Cv::class, 'id_cv');
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'id_post');
    }
}
