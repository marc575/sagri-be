<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'address',
        'region',
        'profile_picture',
        'language_id',
        'bio',
        'land_size',
        'gps_coordinates',
        'farming_since',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getProfilePictureUrlAttribute()
    {
        if (!$this->profile_picture) {
            return null;
        }
    
        return asset("storage/{$this->profile_picture}");
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'user_activities');
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function ordersAsBuyer()
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    public function ordersAsFarmer()
    {
        return $this->hasMany(Order::class, 'farmer_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
