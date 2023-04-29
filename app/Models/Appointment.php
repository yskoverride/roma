<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'date', 'user_id','patient_id',
        'start_time','end_time','cancelled_at'
    ];

    protected $casts = [
        'date' => 'datetime',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public static function booted()
    {
        static::creating(function ($model){
            $model->uuid = Str::uuid();
            $model->token = Str::random(32);
        });
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function scopeNotCancelled(Builder $builder)
    {
        $builder->whereNull('cancelled_at');
    }

    public function isCancelled()
    {
        return ! is_null($this->cancelled_at);
    }
}
