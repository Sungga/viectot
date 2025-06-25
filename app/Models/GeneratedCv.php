<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneratedCv extends Model
{
    protected $table = 'generated_cvs';

    protected $fillable = [
        'user_id',
        'cv_id',
        'name',
        'dob',
        'gender',
        'phone',
        'email',
        'address',
        'avatar_path',
        'career_goal',
        'template',
        'education',
        'experience',
        'projects',
        'skills',
        'certificates',
    ];
}
