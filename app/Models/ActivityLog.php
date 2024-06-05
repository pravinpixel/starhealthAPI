<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivityLog extends Model
{
    use HasFactory,SoftDeletes;
    protected $table='activity_loges';
    protected $fillable = ['user','user_id','message','action','information'];
}
