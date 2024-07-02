<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TruckLoggerModel extends Model
{
    use HasFactory;

    protected $table = 'vehicle_logs';
    protected $primaryKey = 'id';

    protected $fillable = [
        'vehicle_id',
        'api_key',
        'date_stamp',
        'latitude',
        'longitude',
        'speed',
        'direction',
        'created_at',
        'update_at',
    ];
}
