<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject; // Corrected namespace for JWTSubject
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
class Employee extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable,SoftDeletes;

    // Define your model attributes and relationships here
    protected $hidden = [
        'otp',
        'state_id',
        'otp_verified',
        'otp_verified',
        'expired_date',
        'employee_status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    public function getPassportPhotoAttribute($value)
    {
        if ($value === null) {
            return null;
        }
        return config('app.image_url') . $value;
    }
    public function getFamilyPhotoAttribute($value)
    {
        if ($value === null) {
            return null;
        }
        return config('app.image_url') . $value;
    }
    public function getProfilePhotoAttribute($value)
    {
        if ($value === null) {
            return null;
        }
        return config('app.image_url') . $value;
    }
}
