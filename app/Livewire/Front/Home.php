<?php

namespace App\Livewire\Front;

use App\Models\Config;
use App\Models\TwebPenduduk;
use Livewire\Component;

class Home extends Component
{
    public $totalPenduduk, $totalKecamatan, $totalDesa, $totalBantuan;

    public function mount()
    {
        $this->totalPenduduk = TwebPenduduk::count() ?? '0';
        $this->totalKecamatan = Config::distinct('kode_kecamatan')->count();
        $this->totalDesa = Config::orderBy('kode_desa')->count() ?? '0';
        $this->totalBantuan = '0';
    }

    public function render()
    {
        return view('livewire.front.home')->layout('layouts/front/app');
    }
}
