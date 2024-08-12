<?php

namespace App\Livewire;

use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Config;
use App\Models\TwebPenduduk;
use App\Models\TwebWilClusterDesa;

class DataKeluarga extends Component
{
    use WithPagination;

    public $kecamatan, $kecTerpilih, $desa = [], $desTerpilih, $idConfig, $dusun = [], $dusTerpilih, $rt = [], $rtTerpilih;

    public function mount()
    {
        $this->kecamatan = Config::distinct('nama_kecamatan')->pluck('nama_kecamatan', 'nama_kecamatan');
    }

    public function updatedKecTerpilih()
    {
        $this->desa = Config::distinct('nama_desa')->where('nama_kecamatan', $this->kecTerpilih)->pluck('nama_desa', 'nama_desa');
        $this->desTerpilih = [];
        $this->idConfig = [];
        $this->dusTerpilih = [];
    }

    public function updatedDesTerpilih($value)
    {
        $idne = Config::where('nama_desa', $value)->first();
        $this->idConfig = $idne->id;
        $this->dusun = TwebWilClusterDesa::distinct('dusun')->where('config_id', $this->idConfig)->pluck('dusun', 'dusun');
        $this->dusTerpilih = [];
    }

    public function render()
    {
        return view('livewire.data-keluarga', [
            'penduduk' => TwebPenduduk::with('alamate')
            ->join('tweb_wil_clusterdesa', 'tweb_penduduk.config_id', '=', 'tweb_wil_clusterdesa.config_id')
            ->where('tweb_penduduk.config_id', $this->idConfig)
            ->where('tweb_wil_clusterdesa.dusun', $this->dusTerpilih)
            ->paginate(10)
        ]);
    }
}
