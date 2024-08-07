<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject; // Corrected namespace for JWTSubject
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Aws\S3\S3Client;
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
        'passportUrl'
    ];
    // protected $appends = ['passport','profile','family'];
    // public function getPassportAttribute()
    // {
    //     if ($this->passport_photo === null) {
    //         return null;
    //     }
    //            $data=explode('.com/', $this->passport_photo);
    //     try {
    //         $value = Storage::disk('s3')->get($data[1]);
    //         return 'data:image/jpeg;base64,' . base64_encode($value);
    //     } catch (\Exception $e) {
    //         Log::error('Error retrieving S3 object: ' . $e->getMessage());
    //         return null;
    //     }
    // } 
    // public function getProfileAttribute()
    // {
    //     if ($this->profile_photo === null) {
    //         return null;
    //     }
    //            $data=explode('.com/', $this->profile_photo);
    //     try {
    //         $value = Storage::disk('s3')->get($data[1]);
    //         return 'data:image/jpeg;base64,' . base64_encode($value);
    //     } catch (\Exception $e) {
    //         return null;
    //     }
    // } 
    // public function getFamilyAttribute()
    // {
    //     if ($this->family_photo === null) {
    //         return null; 
    //     }
    //     $data = explode('.com/', $this->family_photo);
    //     try {
    //         $value = Storage::disk('s3')->get($data[1]); 
    //         if ($value) {
    //             return 'data:image/jpeg;base64,' . base64_encode($value);
    //         } else {
    //             return null;
    //         }
    //     } catch (\Exception $e) {
    //         return null;
    //     }
    // }
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
       
        $s3Client = new S3Client([
            'region'  =>  env('AWS_DEFAULT_REGION'),
            'version' => 'latest',
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
        $bucket = env('AWS_BUCKET');
        $key =$value;
        $cmd = $s3Client->getCommand('GetObject', [
            'Bucket' => $bucket,
            'Key'    => $key
        ]);

        $request = $s3Client->createPresignedRequest($cmd, '+20 minutes');
        $presignedUrl1 = (string) $request->getUri();
        return $presignedUrl1;
    }
    public function getFamilyPhotoAttribute($value)
    {
        if ($value === null) {
            return null;
        }
        
        $s3Client = new S3Client([
            'region'  =>  env('AWS_DEFAULT_REGION'),
            'version' => 'latest',
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
        $bucket = env('AWS_BUCKET');
        $key =$value;
        $cmd = $s3Client->getCommand('GetObject', [
            'Bucket' => $bucket,
            'Key'    => $key
        ]);

$request = $s3Client->createPresignedRequest($cmd, '+20 minutes');
$presignedUrl = (string) $request->getUri();
return $presignedUrl;
    }
    public function getProfilePhotoAttribute($value)
    {
        if ($value === null) {
            return null;
        }

                        $s3Client = new S3Client([
                        'region'  =>  env('AWS_DEFAULT_REGION'),
                        'version' => 'latest',
                        'credentials' => [
                            'key'    => env('AWS_ACCESS_KEY_ID'),
                            'secret' => env('AWS_SECRET_ACCESS_KEY'),
                        ],
                    ]);
                    $bucket = env('AWS_BUCKET');
                    $key =$value;
                    $cmd = $s3Client->getCommand('GetObject', [
                        'Bucket' => $bucket,
                        'Key'    => $key
                    ]);

            $request = $s3Client->createPresignedRequest($cmd, '+20 minutes');
            $presignedUrl = (string) $request->getUri();
            return $presignedUrl;
 
    }
}
