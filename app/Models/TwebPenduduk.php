<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TwebPenduduk extends Model
{
    use HasFactory;

    protected $table = 'tweb_penduduk';
    protected $guarded = [];

    public function TwebPendudukKk()
    {
        return $this->hasMany(TwebPendudukPendidikanKk::class, 'id', 'pendidikan_kk_id');
    }

    public function alamate()
    {
        return $this->belongsTo(TwebWilClusterDesa::class, 'id_cluster', 'id');
    }
}
