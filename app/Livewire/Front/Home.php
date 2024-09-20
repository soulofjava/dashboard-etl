<?php

namespace App\Livewire\Front;

use App\Models\Config;
use App\Models\TwebPenduduk;
use Livewire\Component;
use Illuminate\Support\Facades\Cache;

class Home extends Component
{
    public $totalPenduduk, $totalKecamatan, $totalDesa, $totalBantuan;

    public function mount()
    {
        $this->totalPenduduk =   Cache::remember('totalPenduduk', 60, function () {
            return TwebPenduduk::count() ?? '0';
        });
        $this->totalKecamatan =  Cache::remember('totalKecamatan', 60, function () {
            return Config::distinct('kode_kecamatan')->count();
        });
        $this->totalDesa =  Cache::remember('totalDesa', 60, function () {
            return Config::orderBy('kode_desa')->count() ?? '0';
        });
        $this->totalBantuan = Cache::remember('totalBantuan', 60, function () {
            return '0';
        });
    }

    public function render()
    {
        return view('livewire.front.home')->layout('layouts/front/app');
    }
}