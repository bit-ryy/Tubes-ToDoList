<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    /**
     * ENKAPSULASI: $fillable
     * Melindungi kolom ID dan timestamps dari injeksi data secara langsung.
     */
    protected $fillable = [
        'user_id',
        'project_id',
        'parent_id',
        'title',
        'description',
        'deadline',
        'priority',
        'status',
    ];

    /**
     * RELASI OOP
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function labels()
    {
        return $this->belongsToMany(Label::class, 'label_task');
    }

    public function parent()
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }

    public function subtasks()
    {
        return $this->hasMany(Task::class, 'parent_id');
    }
}
