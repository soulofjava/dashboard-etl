<?php

namespace App\Livewire;

use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Config;
use App\Models\RefPendudukHamil;
use App\Models\TwebCacat;
use App\Models\TwebCaraKB;
use App\Models\TwebGolonganDarah;
use App\Models\TwebPenduduk;
use App\Models\TwebPendudukAgama;
use App\Models\TwebPendudukKawin;
use App\Models\TwebPendudukPekerjaan;
use App\Models\TwebPendudukPendidikanKk;
use App\Models\TwebPendudukSex;
use App\Models\TwebSakitMenahun;
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
        $jkel,
        $jkelTerpilih,
        $agama,
        $agamaTerpilih,
        $kawin,
        $kawinTerpilih,
        $pendidikan,
        $pendidikanTerpilih,
        $pekerjaan,
        $pekerjaanTerpilih,
        $golDarah,
        $golDarahTerpilih,
        $hamil,
        $hamilTerpilih,
        $kb,
        $kbTerpilih,
        $cacat,
        $cacatTerpilih,
        $sakit,
        $sakitTerpilih,
        $rtTerpilih = [];

    public function mount()
    {
        $this->jkel = TwebPendudukSex::all()->pluck('nama', 'id');
        $this->agama = TwebPendudukAgama::all()->pluck('nama', 'id');
        $this->kawin = TwebPendudukKawin::all()->pluck('nama', 'id');
        $this->pendidikan = TwebPendudukPendidikanKk::all()->pluck('nama', 'id');
        $this->pekerjaan = TwebPendudukPekerjaan::all()->pluck('nama', 'id');
        $this->golDarah = TwebGolonganDarah::all()->pluck('nama', 'id');
        $this->hamil = RefPendudukHamil::all()->pluck('nama', 'id');
        $this->cacat = TwebCacat::all()->pluck('nama', 'id');
        $this->kb = TwebCaraKB::all()->pluck('nama', 'id');
        $this->sakit = TwebSakitMenahun::all()->pluck('nama', 'id');
        $this->kecamatan = Config::distinct('nama_kecamatan')->pluck('nama_kecamatan', 'nama_kecamatan');
    }

    public function updatedRwTerpilih()
    {
        $this->rt = TwebWilClusterDesa::distinct('rt')->where('config_id', $this->idConfig)->pluck('rt', 'rt');
    }

    public function updatedJkelTerpilih()
    {
        if ($this->jkelTerpilih != 2) {
            $this->reset(['kbTerpilih', 'hamilTerpilih']);
        }
    }

    public function updatedKecTerpilih()
    {
        $this->desa = Config::distinct('nama_desa')->where('nama_kecamatan', $this->kecTerpilih)->pluck('nama_desa', 'nama_desa');
        $this->reset(['desTerpilih', 'idConfig', 'dusTerpilih', 'dusTerpilih', 'rwTerpilih', 'rtTerpilih', 'jkelTerpilih', 'agamaTerpilih', 'kawinTerpilih', 'pendidikanTerpilih', 'pekerjaanTerpilih', 'golDarahTerpilih', 'hamilTerpilih', 'cacatTerpilih', 'kbTerpilih']);
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
                    if ($this->jkelTerpilih) {
                        $a->where('sex', $this->jkelTerpilih);
                    }
                    if ($this->agamaTerpilih) {
                        $a->where('agama_id', $this->agamaTerpilih);
                    }
                    if ($this->kawinTerpilih) {
                        $a->where('status_kawin', $this->kawinTerpilih);
                    }
                    if ($this->pendidikanTerpilih) {
                        $a->where('pendidikan_kk_id', $this->pendidikanTerpilih);
                    }
                    if ($this->pekerjaanTerpilih) {
                        $a->where('pekerjaan_id', $this->pekerjaanTerpilih);
                    }
                    if ($this->golDarahTerpilih) {
                        $a->where('golongan_darah_id', $this->golDarahTerpilih);
                    }
                    if ($this->cacatTerpilih) {
                        $a->where('cacat_id', $this->cacatTerpilih);
                    }
                    if ($this->sakitTerpilih) {
                        $a->where('sakit_menahun_id', $this->sakitTerpilih);
                    }
                    if ($this->jkelTerpilih == 2) {
                        if ($this->hamilTerpilih) {
                            $a->where('hamil', $this->hamilTerpilih);
                        }
                        if ($this->kbTerpilih) {
                            $a->where('cara_kb_id', $this->kbTerpilih);
                        }
                    }
                })
                ->where('tweb_penduduk.config_id', $this->idConfig)
                ->paginate(10)
        ]);
    }
}
