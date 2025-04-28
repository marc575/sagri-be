<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'category', 'description'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_activities');
    }
}
