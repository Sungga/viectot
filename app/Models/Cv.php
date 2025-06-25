<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cv extends Model
{
    protected $fillable = ['id_user', 'file_name', 'name', 'status'];

    public static function userHasCV($userId)
    {
        return self::where('id_user', $userId)->exists();
    }
}
