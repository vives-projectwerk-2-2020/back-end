<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    protected $fillable = ['name', 'location_id', 'description','city','latitude','longitude','address'];
    public $timestamps = false;
}