<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    protected $fillable = ['guid', 'name', 'latitude', 'longitude', 'city', 'address', 'description'];
    protected $primaryKey = 'guid';
    protected $keyType = 'uuid';
    public $timestamps = false;
    public $incrementing = false;
}
