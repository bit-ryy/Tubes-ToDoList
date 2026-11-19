<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    /**
     * ENKAPSULASI: $fillable
     * Melindungi kolom ID dan timestamps dari injeksi data secara langsung.
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'deadline',
        'priority',
        'status',
    ];

    /**
     * RELASI OOP: Belongs-To
     * Setiap Task pasti dimiliki oleh satu User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
