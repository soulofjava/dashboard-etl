<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TwebPendudukPendidikanKk extends Model
{
    use HasFactory;

    protected $table = 'tweb_penduduk_pendidikan_kk';
    protected $guarded = [];

    public function TwebPenduduk()
    {
        return $this->hasMany(TwebPenduduk::class, 'pendidikan_kk_id');
    }
}
