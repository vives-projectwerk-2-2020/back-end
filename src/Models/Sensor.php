<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    protected $fillable = ['id', 'name', 'latitude', 'longitude', 'city', 'address', 'description'];
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'string';
}