<?php

namespace App\Livewire\Front;

use App\Models\Config;
use Livewire\Component;
use Illuminate\Support\Facades\Cache;
use Livewire\WithPagination;

class ListKelurahan extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    // public $kelurahan;

    // public function mount(){
    //     $this->kelurahan =


    // }
    public function render()
    {
        $currentPage = request()->get('page', 1); // Get the current page from the request, default to 1
        $cacheKey = "kelurahan_page_{$currentPage}";

        $kelurahan = Cache::remember($cacheKey, 60, function () {
            return Config::withCount('keluarga')
                ->withCount('penduduk')
                ->withCount('rtm')
                ->paginate(10);
        });
        return view('livewire.front.list-kelurahan', [
            'kelurahan' => $kelurahan
        ]);
    }
}
