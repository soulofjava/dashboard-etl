<?php

namespace App\Livewire\Front;

use App\Models\Config;
use Livewire\Component;

class ListKelurahan extends Component
{
    public $kelurahan;

    public function mount(){
        $this->kelurahan = Config::get();
    }
    public function render()
    {
        return view('livewire.front.list-kelurahan');
    }
}
