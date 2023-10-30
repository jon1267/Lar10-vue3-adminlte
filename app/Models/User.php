<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\RoleType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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

    // this with getFormattedCreatedAtAttribute() & getRole...() methods, add 'formatted_created_at' & role_name
    // to Response object, which visible in vue modules as {{ user.formatted_created_at}} & {{ user.role_name }}
    protected $appends = ['formatted_created_at', 'role_name'];

    public function getFormattedCreatedAtAttribute()
    {
        //return $this->created_at->format(config('app.date_format'));
        return $this->created_at->format(setting('date_format'));
    }

    public function getRoleNameAttribute()
    {
        return RoleType::getRoleBy($this->role);
    }

    public function avatar(): Attribute
    {
        return Attribute::make(
            //get: fn($value) => asset(Storage::url($value)) ?? asset('/noimage.png'), //bug, asset() always present
            get: fn($value) => (is_null($value)) ? asset('/noimage.png') : asset(Storage::url($value)),
        );
    }

}
