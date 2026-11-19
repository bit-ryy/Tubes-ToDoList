<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * ENKAPSULASI: $fillable
     * Mengatur atribut apa saja yang boleh diisi secara massal (Mass Assignment).
     */
    protected $fillable = [
        'username',
        'password',
    ];

    /**
     * ENKAPSULASI: $hidden
     * Menyembunyikan atribut sensitif agar tidak pernah ikut tampil saat data diubah ke array/JSON (REST API).
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * RELASI OOP: One-to-Many
     * Satu User bisa memiliki banyak Task.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
