<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;

    protected $table = 'config';
    protected $guarded = [];

    public function penduduk(){
        return $this->hasMany(TwebPenduduk::class, "config_id");
    }

    public function keluarga(){
        return $this->hasMany(TwebKeluarga::class, "config_id");
    }

    public function rtm(){
        return $this->hasMany(TwebRtm::class, "config_id");
    }
}
