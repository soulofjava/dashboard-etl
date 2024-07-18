<?php

namespace App\Livewire\Front;

use App\Models\Config;
use Livewire\Component;
use Illuminate\Support\Facades\Cache;


class ListKelurahan extends Component
{
    public $kelurahan;

    public function mount(){
        $this->kelurahan =
        Cache::remember('kelurahan', 60, function () {
         return Config::withCount('keluarga')->withCount('penduduk')->withCount('rtm')->get();
        });

    }
    public function render()
    {
        return view('livewire.front.list-kelurahan');
    }
}
