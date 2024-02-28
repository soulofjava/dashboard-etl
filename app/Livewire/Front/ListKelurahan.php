<?php

namespace App\Livewire\Front;

use Livewire\Component;

class ListKelurahan extends Component
{
    public function render()
    {
        return view('livewire.front.list-kelurahan')->layout('layouts/front/app');
    }
}
