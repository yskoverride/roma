<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = ['date','start_time','end_time','user_id','DDMMYYYY'];

    protected $casts = [
        'date' => 'datetime',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public static $rules = [
        'date' => 'required|date',
        'user_id' => 'required|integer',
        'start' => 'required|date_format:H:i|before:end',
        'end' => 'required|date_format:H:i|after:start',
    ];


    public function unavailabilities()
    {
        return $this->hasMany(ScheduleUnavailability::class);
    }
}
