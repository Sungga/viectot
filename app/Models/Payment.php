<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';

    protected $fillable = [
        'id_user',
        'txn_ref',
        'order_info',
        'transaction_no',
        'bank_code',
        'amount',
        'pay_date',
        'raw_data',
        'status',
    ];

    protected $casts = [
        'raw_data' => 'array',
        'pay_date' => 'datetime',
    ];

    // Quan hệ nếu bạn có bảng candidates
    public function user()
    {
        return $this->belongsTo(Candidate::class, 'id_user', 'id_user');
    }

    // Scope tiện lợi để lấy theo trạng thái
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }
}