<?php

namespace App\Livewire;

use App\Models\Config;
use App\Models\TwebPenduduk;
use Livewire\Component;

class DataKeluarga extends Component
{
    public $kecamatan, $desa;

    public function mount()
    {
        $this->kecamatan = Config::distinct('nama_kecamatan')->pluck('nama_kecamatan', 'nama_kecamatan') ?? [];
        $this->desa = [];
    }

    // public function cariDesa($selectedKecamatan)
    // {
    //     $this->$selectedKecamatan = 'asd';
    //     // if (!empty($this->kecamatan)) {
    //     //     foreach ($this->kecamatan as $kecamatan) {
    //     //         $desaKecamatan = Config::where('nama_kecamatan', $kecamatan)->distinct('nama_desa')->pluck('nama_desa', 'nama_desa')->toArray();
    //     //         $this->desa[$kecamatan] = $desaKecamatan;
    //     //     }
    //     // }
    // }

    public function render()
    {
        return view('livewire.data-keluarga');
    }
}
