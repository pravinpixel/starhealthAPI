<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject; // Corrected namespace for JWTSubject
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
class Employee extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable,SoftDeletes;

    // Define your model attributes and relationships here
    protected $hidden = [
        'token',
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
        try {
            $file =  Storage::disk('s3')->temporaryUrl($value, now()->addMinutes(60));
            return $file;
        } catch (\Exception $e) {
            return null;
        }
    }
    public function getFamilyPhotoAttribute($value)
    {
        if ($value === null) {
            return null;
        }
        try {
            $file =  Storage::disk('s3')->temporaryUrl($value, now()->addMinutes(60));
            return $file;
        } catch (\Exception $e) {
            return null;
        }
    }
    public function getProfilePhotoAttribute($value)
    {
        if ($value === null) {
            return null;
        }
        try {
            $file =  Storage::disk('s3')->temporaryUrl($value, now()->addMinutes(60));
            return $file;
        } catch (\Exception $e) {
            return null;
        }
    }
}
