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

    public $kecamatan,
        $kecTerpilih,
        $desa = [],
        $desTerpilih,
        $idConfig,
        $dusun = [],
        $dusTerpilih = [],
        $rw = [],
        $rwTerpilih = [],
        $rt = [],
        $rtTerpilih = [];

    public function mount()
    {
        $this->kecamatan = Config::distinct('nama_kecamatan')->pluck('nama_kecamatan', 'nama_kecamatan');
    }

    public function updatedRwTerpilih()
    {
        $this->rt = TwebWilClusterDesa::distinct('rt')->where('config_id', $this->idConfig)->pluck('rt', 'rt');
    }

    public function updatedKecTerpilih()
    {
        $this->desa = Config::distinct('nama_desa')->where('nama_kecamatan', $this->kecTerpilih)->pluck('nama_desa', 'nama_desa');
        $this->reset(['desTerpilih', 'idConfig', 'dusTerpilih', 'dusTerpilih', 'rwTerpilih']);
    }

    public function updatedDesTerpilih($value)
    {
        $idne = Config::where('nama_desa', $value)->first();
        $this->idConfig = $idne->id;
        $this->dusun = TwebWilClusterDesa::distinct('dusun')->where('config_id', $this->idConfig)->pluck('dusun', 'dusun');
        $this->rw = TwebWilClusterDesa::distinct('rw')->where('config_id', $this->idConfig)->pluck('rw', 'rw');
        $this->reset(['dusTerpilih']);
    }

    public function render()
    {
        return view('livewire.data-keluarga', [
            'penduduk' => TwebPenduduk::with('alamate')
                ->whereHas('alamate', function ($isa) {
                    $a =  $isa->where('dusun', $this->dusTerpilih);
                    if ($this->rwTerpilih) {
                        $a->where('rw', $this->rwTerpilih);
                    }
                    if ($this->rtTerpilih) {
                        $a->where('rt', $this->rtTerpilih);
                    }
                })
                ->where('tweb_penduduk.config_id', $this->idConfig)
                ->paginate(10)
        ]);
    }
}
