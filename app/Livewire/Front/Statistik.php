<?php

namespace App\Livewire\Front;

use App\Livewire\Front\Chart\ColumnChart;
use App\Models\Config;
use App\Models\Program;
use App\Models\TwebKeluarga;
use App\Models\TwebPenduduk;
use App\Models\TwebPendudukPendidikanKk;
use App\Models\TwebPendudukUmur;
use App\Models\TwebRtm;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;
use Asantibanez\LivewireCharts\Models\PieChartModel;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\Cache;

class Statistik extends Component
{
    public $selectKecamatan, $selectDesa, $listDesa, $show, $jenis = '', $configId, $data, $cari, $judul;
    public $totalPendudukDesa, $totalKeluargaDesa, $rtmDesa, $bantuanDesa;
    public $firstRun = true;
    protected $listeners = [
        'onPointClick' => 'handleOnPointClick',
        'onSliceClick' => 'handleOnSliceClick',
        'onColumnClick' => 'handleOnColumnClick',
    ];

    public function updateDesa()
    {
        $this->listDesa = Config::where('kode_kecamatan', $this->selectKecamatan)->get();
        $this->selectDesa = "";
    }

    public function handleOnColumnClick($column)
    {
        dd($column);
    }
    public function rentangUmur()
    {
        $this->jenis = "rentangUmur";
        $this->judul = 'Rentang Umur';
        $this->data = TwebPenduduk::join('tweb_penduduk_umur', function ($join) {
            $join->on(DB::raw('TIMESTAMPDIFF(YEAR, tweb_penduduk.tanggallahir, CURRENT_DATE)'), '>=', 'tweb_penduduk_umur.dari')
                ->on(DB::raw('TIMESTAMPDIFF(YEAR, tweb_penduduk.tanggallahir, CURRENT_DATE)'), '<=', 'tweb_penduduk_umur.sampai');
        })
            ->where('tweb_penduduk.config_id', '=', $this->configId)
            ->whereBetween('tweb_penduduk_umur.id', [6, 37])
            ->groupBy('tweb_penduduk_umur.id', 'tweb_penduduk_umur.nama')
            ->orderBy('tweb_penduduk_umur.nama', 'asc')
            ->select('tweb_penduduk_umur.id as id', DB::raw('COALESCE(tweb_penduduk_umur.nama, "Belum Terdata") AS nama'), DB::raw('COUNT(*) as total'))
            ->get();

        $this->dispatch('column', data:$this->data);
    }
    public function kategoriUmur()
    {
        $this->jenis = "kategoriUmur";
        $this->judul = 'Kategori Umur';
        $this->data = TwebPenduduk::join('tweb_penduduk_umur', function ($join) {
            $join->on(DB::raw('TIMESTAMPDIFF(YEAR, tweb_penduduk.tanggallahir, CURRENT_DATE)'), '>=', 'tweb_penduduk_umur.dari')
                ->on(DB::raw('TIMESTAMPDIFF(YEAR, tweb_penduduk.tanggallahir, CURRENT_DATE)'), '<=', 'tweb_penduduk_umur.sampai');
        })
            ->where('tweb_penduduk.config_id', '=', $this->configId)
            ->whereBetween('tweb_penduduk_umur.id', [1, 4])
            ->groupBy('tweb_penduduk_umur.id', 'tweb_penduduk_umur.nama')
            ->orderBy('tweb_penduduk_umur.nama', 'asc')
            ->select('tweb_penduduk_umur.id as id', DB::raw('COALESCE(tweb_penduduk_umur.nama, "Belum Terdata") AS nama'), DB::raw('COUNT(*) as total'))
            ->get();
        $this->dispatch('column', data:$this->data);
    }
    public function pendidikanKk()
    {
        $this->jenis = "pendidikan_kk";
        $this->judul = 'Pendidikan Dalam KK';
        $this->data = DB::table('tweb_penduduk')
        ->leftjoin('tweb_penduduk_pendidikan_kk', 'tweb_penduduk.pendidikan_kk_id', '=', 'tweb_penduduk_pendidikan_kk.id')
        ->select(
            'tweb_penduduk_pendidikan_kk.id',
            DB::raw('COALESCE(tweb_penduduk_pendidikan_kk.nama, "Belum Terdata") AS nama'),
            DB::raw('COUNT(tweb_penduduk_pendidikan_kk.id) AS total')
        )
        ->where('tweb_penduduk.config_id', '=', $this->configId)
        ->groupBy('tweb_penduduk_pendidikan_kk.id')
        ->orderBy('tweb_penduduk_pendidikan_kk.id', 'asc')->get();
        $this->dispatch('column', data:$this->data);
    }

    public function pendidikanDitempuh()
    {
        $this->jenis = "pendidikan"; 
        $this->judul = 'Pendidikan Sedang Ditempuh';
        $this->data = DB::table('tweb_penduduk')
                ->leftJoin('tweb_penduduk_pendidikan', 'tweb_penduduk.pendidikan_sedang_id', '=', 'tweb_penduduk_pendidikan.id')
                ->select(
                    'tweb_penduduk_pendidikan.id',
                    DB::raw('COALESCE(tweb_penduduk_pendidikan.nama, "Belum Terdata") AS nama'),
                    DB::raw('COUNT(tweb_penduduk.id) AS total')
                )
                ->where('tweb_penduduk.config_id', '=', $this->configId)
                ->groupBy('tweb_penduduk_pendidikan.id', 'nama')
                ->orderBy('tweb_penduduk_pendidikan.id', 'asc')
                ->get();
        $this->dispatch('column', data:$this->data);
    }
    public function pekerjaan()
    {
        $this->jenis = "pekerjaan";
        $this->judul = 'Pekerjaan';
        $this->data = DB::table('tweb_penduduk')
                ->leftJoin('tweb_penduduk_pekerjaan', 'tweb_penduduk.pekerjaan_id', '=', 'tweb_penduduk_pekerjaan.id')
                ->select(
                    'tweb_penduduk_pekerjaan.id',
                    DB::raw('COALESCE(tweb_penduduk_pekerjaan.nama, "Belum Terdata") AS nama'),
                    DB::raw('COUNT(tweb_penduduk.id) AS total')
                )
                ->where('tweb_penduduk.config_id', '=', $this->configId)
                ->groupBy('tweb_penduduk_pekerjaan.id', 'nama')
                ->orderBy('tweb_penduduk_pekerjaan.id', 'asc')->get();
        $this->dispatch('column', data:$this->data);
    }
    public function statusPerkawinan()
    {
        $this->jenis = "statusPerkawinan";
        $this->judul = 'Status Perkawinan';
        $this->data =  DB::table('tweb_penduduk')
                ->leftJoin('tweb_penduduk_kawin', 'tweb_penduduk.status_kawin', '=', 'tweb_penduduk_kawin.id')
                ->select(
                    'tweb_penduduk_kawin.id',
                    DB::raw('COALESCE(tweb_penduduk_kawin.nama, "Belum Terdata") AS nama'),
                    DB::raw('COUNT(tweb_penduduk.id) AS total')
                )
                ->where('tweb_penduduk.config_id', '=', $this->configId)
                ->groupBy('tweb_penduduk_kawin.id', 'nama')
                ->orderBy('tweb_penduduk_kawin.id', 'asc')
                ->get();
        $this->dispatch('column', data:$this->data);
    }
    public function agama()
    {
        $this->jenis = "agama";
        $this->judul = 'Agama';
        $this->data = DB::table('tweb_penduduk')
                ->leftJoin('tweb_penduduk_agama', 'tweb_penduduk.agama_id', '=', 'tweb_penduduk_agama.id')
                ->select(
                    'tweb_penduduk_agama.id',
                    DB::raw('COALESCE(tweb_penduduk_agama.nama, "Belum Terdata") AS nama'),
                    DB::raw('COUNT(tweb_penduduk.id) AS total')
                )
                ->where('tweb_penduduk.config_id', '=', $this->configId)
                ->groupBy('tweb_penduduk_agama.id', 'nama')
                ->orderBy('tweb_penduduk_agama.id', 'asc')->get();
        $this->dispatch('column', data:$this->data);
    }
    public function jenisKelamin()
    {
        $this->jenis = "jenisKelamin";
        $this->judul = 'Jenis Kelamin';
        $this->data = DB::table('tweb_penduduk')
                ->leftJoin('tweb_penduduk_sex', 'tweb_penduduk.sex', '=', 'tweb_penduduk_sex.id')
                ->select(
                    'tweb_penduduk_sex.id',
                    DB::raw('COALESCE(tweb_penduduk_sex.nama, "Belum Terdata") AS nama'),
                    DB::raw('COUNT(tweb_penduduk.id) AS total')
                )
                ->where('tweb_penduduk.config_id', '=', $this->configId)
                ->groupBy('tweb_penduduk_sex.id', 'nama')
                ->orderBy('tweb_penduduk_sex.id', 'asc')->get();
        $this->dispatch('column', data:$this->data);
    }
    public function hubunganKk()
    {
        $this->jenis = "hubunganKk";
        $this->judul = 'Hubungan Dalam KK';
        $this->data = DB::table('tweb_penduduk')
        ->leftJoin('tweb_penduduk_hubungan', 'tweb_penduduk.kk_level', '=', 'tweb_penduduk_hubungan.id')
        ->select(
            'tweb_penduduk_hubungan.id',
            DB::raw('COALESCE(tweb_penduduk_hubungan.nama, "Belum Terdata") AS nama'),
            DB::raw('COUNT(tweb_penduduk.id) AS total')
        )
        ->where('tweb_penduduk.config_id', '=', $this->configId)
        ->groupBy('tweb_penduduk_hubungan.id', 'nama')
        ->orderBy('tweb_penduduk_hubungan.id', 'asc')->get();
        $this->dispatch('column', data:$this->data);
    }
    public function wargaNegara()
    {
        $this->jenis = "wargaNegara";
        $this->judul = 'Warga Negara';
        $this->data = DB::table('tweb_penduduk')
        ->leftJoin('tweb_penduduk_warganegara', 'tweb_penduduk.warganegara_id', '=', 'tweb_penduduk_warganegara.id')
        ->select(
            'tweb_penduduk_warganegara.id',
            DB::raw('COALESCE(tweb_penduduk_warganegara.nama, "Belum Terdata") AS nama'),
            DB::raw('COUNT(tweb_penduduk.id) AS total')
        )
        ->where('tweb_penduduk.config_id', '=', $this->configId)
        ->groupBy('tweb_penduduk_warganegara.id', 'nama')
        ->orderBy('tweb_penduduk_warganegara.id', 'asc')->get();
        $this->dispatch('column', data:$this->data);
    }
    public function statusPenduduk()
    {
        $this->jenis = "statusPenduduk";
        $this->judul = 'Status Penduduk';
        $this->data = DB::table('tweb_penduduk')
                ->leftJoin('tweb_penduduk_status', 'tweb_penduduk.status', '=', 'tweb_penduduk_status.id')
                ->select(
                    'tweb_penduduk_status.id',
                    DB::raw('COALESCE(tweb_penduduk_status.nama, "Belum Terdata") AS nama'),
                    DB::raw('COUNT(tweb_penduduk.id) AS total')
                )
                ->where('tweb_penduduk.config_id', '=', $this->configId)
                ->groupBy('tweb_penduduk_status.id', 'nama')
                ->orderBy('tweb_penduduk_status.id', 'asc')->get();
        $this->dispatch('column', data:$this->data);
    }
    public function golonganDarah()
    {
        $this->jenis = "golonganDarah";
        $this->judul = 'Golongan Darah';
        $this->data = DB::table('tweb_penduduk')
        ->leftJoin('tweb_golongan_darah', 'tweb_penduduk.golongan_darah_id', '=', 'tweb_golongan_darah.id')
        ->select(
            'tweb_golongan_darah.id',
            DB::raw('COALESCE(tweb_golongan_darah.nama, "Belum Terdata") AS nama'),
            DB::raw('COUNT(tweb_penduduk.id) AS total')
        )
        ->where('tweb_penduduk.config_id', '=', $this->configId)
        ->groupBy('tweb_golongan_darah.id', 'nama')
        ->orderBy('tweb_golongan_darah.id', 'asc')->get();
        $this->dispatch('column', data:$this->data);
    }
    public function penyandangCacat()
    {
        $this->jenis = "penyandangCacat";
        $this->judul = 'Penyandang Cacat';
        $this->data = DB::table('tweb_penduduk')
        ->leftJoin('tweb_cacat', 'tweb_penduduk.cacat_id', '=', 'tweb_cacat.id')
        ->select(
            'tweb_cacat.id',
            DB::raw('COALESCE(tweb_cacat.nama, "Belum Terdata") AS nama'),
            DB::raw('COUNT(tweb_penduduk.id) AS total')
        )
        ->where('tweb_penduduk.config_id', '=', $this->configId)
        ->groupBy('tweb_cacat.id', 'nama')
        ->orderBy('tweb_cacat.id', 'asc')->get();
        $this->dispatch('column', data:$this->data);
    }
    public function penyakitMenahun()
    {
        $this->jenis = "penyakitMenahun";
        $this->judul = 'Penyandang Cacat';
        $this->data = DB::table('tweb_penduduk')
        ->leftJoin('tweb_sakit_menahun', 'tweb_penduduk.sakit_menahun_id', '=', 'tweb_sakit_menahun.id')
        ->select(
            'tweb_sakit_menahun.id',
            DB::raw('COALESCE(tweb_sakit_menahun.nama, "Belum Terdata") AS nama'),
            DB::raw('COUNT(tweb_penduduk.id) AS total')
        )
        ->where('tweb_penduduk.config_id', '=', $this->configId)
        ->groupBy('tweb_sakit_menahun.id', 'nama')
        ->orderBy('tweb_sakit_menahun.id', 'asc')->get();
        $this->dispatch('column', data:$this->data);
    }
    public function akseptorKB()
    {
        $this->jenis = "akseptorKB";
        $this->judul = 'Penyandang Cacat';
        $this->data = DB::table('tweb_penduduk')
        ->leftJoin('tweb_cara_kb', 'tweb_penduduk.cara_kb_id', '=', 'tweb_cara_kb.id')
        ->select(
            'tweb_cara_kb.id',
            DB::raw('COALESCE(tweb_cara_kb.nama, "Belum Terdata") AS nama'),
            DB::raw('COUNT(tweb_penduduk.id) AS total')
        )
        ->where('tweb_penduduk.config_id', '=', $this->configId)
        ->groupBy('tweb_cara_kb.id', 'nama')
        ->orderBy('tweb_cara_kb.id', 'asc')->get();
        $this->dispatch('column', data:$this->data);
    }
    public function kepemilikanKtp()
    {
        $this->jenis = "kepemilikanKtp";
        $this->judul = 'Kepemilikan KTP';
        $this->data = DB::table('tweb_penduduk')
        ->leftJoin('tweb_status_ktp', 'tweb_penduduk.status_rekam', '=', 'tweb_status_ktp.status_rekam')
        ->select(
            'tweb_status_ktp.status_rekam',
            DB::raw('COALESCE(tweb_status_ktp.nama, "Belum Terdata") AS nama'),
            DB::raw('COUNT(tweb_penduduk.id) AS total')
        )
        ->where('tweb_penduduk.config_id', '=', $this->configId)
        ->groupBy('tweb_status_ktp.status_rekam', 'nama')
        ->orderBy('tweb_status_ktp.status_rekam', 'asc')->get();
        $this->dispatch('column', data:$this->data);
    }
    public function asuransiKesehatan()
    {
        $this->jenis = "asuransiKesehatan";
        $this->judul = 'Asuransi Kesehatan';
        $this->data = DB::table('tweb_penduduk')
        ->leftJoin('tweb_penduduk_asuransi', 'tweb_penduduk.id_asuransi', '=', 'tweb_penduduk_asuransi.id')
        ->select(
            'tweb_penduduk_asuransi.id',
            DB::raw('COALESCE(tweb_penduduk_asuransi.nama, "Belum Terdata") AS nama'),
            DB::raw('COUNT(tweb_penduduk.id) AS total')
        )
        ->where('tweb_penduduk.config_id', '=', $this->configId)
        ->groupBy('tweb_penduduk_asuransi.id', 'nama')
        ->orderBy('tweb_penduduk_asuransi.id', 'asc')->get();
        $this->dispatch('column', data:$this->data);
    }
    public function statusCovid()
    {
        $this->jenis = "statusCovid";
        $this->judul = 'Status Covid';
    }
    public function sukuEtnis()
    {
        $this->jenis = "sukuEtnis";
        $this->judul = 'Suku / Etnis';
        // $this->data = DB::table(DB::raw('(SELECT COALESCE(suku, \'\') AS suku FROM tweb_penduduk) as a'))
        // ->select('a.suku', DB::raw('COUNT(a.suku) as jumlah'))
        // ->groupBy('a.suku')
        // ->get();
        $value = Cache::remember("suku-".$this->configId,60*60, function () {
            return  DB::select("SELECT a.suku AS nama, COUNT(a.suku) AS total 
            FROM 
                (SELECT 
                    CASE 
                        WHEN suku IS NULL THEN 'Belum Terdata' 
                        WHEN suku = '' THEN 'Belum Terdata' 
                        ELSE suku 
                    END AS suku 
                FROM tweb_penduduk) a 
            GROUP BY a.suku;");
        });
         $this->data = $value;
       
        
        $this->dispatch('column', data:$this->data);
    }
    public function bpjsKetenagakerjaan()
    {
        $this->jenis = "bpjsKetenagakerjaan";
        $this->judul = 'BPJS Ketenagakerjaan';
    }
    public function statusKehamilan()
    {
        $this->jenis = "statusKehamilan";
        $this->judul = 'Status Kehamilan';
    }
    public function kelasSosial()
    {
        $this->jenis = "kelasSosial";
        $this->judul = 'kelas Sosial';
        $this->data = DB::select('SELECT u.*, 
                COUNT(k.id) AS total,
                COUNT(CASE WHEN k.kelas_sosial = u.id AND p.sex = 1 THEN p.id END) AS laki,
                COUNT(CASE WHEN k.kelas_sosial = u.id AND p.sex = 2 THEN p.id END) AS perempuan
            FROM tweb_keluarga_sejahtera AS u
            LEFT JOIN (tweb_keluarga AS k LEFT JOIN tweb_penduduk AS p ON k.nik_kepala = p.id) ON k.kelas_sosial = u.id 
            WHERE k.config_id = '.$this->configId.'
            GROUP BY u.id
            ORDER BY u.id ASC;');

        $this->dispatch('column', data:$this->data);
    }
    public function bantuanPenduduk()
    {
        $this->jenis = "bantuanPenduduk";
        $this->judul = 'Bantuan Penduduk';
    }
    public function bantuanKeluarga()
    {
        $this->jenis = "bantuanKeluarga";
        $this->judul = 'Bantuan Keluarga';
    }
    public function rtm()
    {
        $this->jenis = "rtm";
        $this->judul = 'RTM';
    }
    public function tampilkan()
    {
        $rules = [
            'selectKecamatan' => 'required',
            'selectDesa' => 'required'
        ];
        $this->validate($rules);

        $this->cari = Config::find($this->selectDesa);
        if (isset($this->cari)) {
            $this->show = true;
            $this->jenis = "rentangUmur";
            $this->configId = $this->cari->id;
        }
            $this->judul = 'Rentang Umur';
            $this->totalPendudukDesa = TwebPenduduk::where('config_id', '=', $this->configId)->count();
            $this->totalKeluargaDesa = TwebKeluarga::where('config_id', '=', $this->configId)->count();
            $this->rtmDesa = TwebRtm::where('config_id', '=', $this->configId)->count();
            $this->bantuanDesa =  Program::where('config_id', '=' , $this->configId)->count();
            $this->data = TwebPenduduk::join('tweb_penduduk_umur', function ($join) {
                $join->on(DB::raw('TIMESTAMPDIFF(YEAR, tweb_penduduk.tanggallahir, CURRENT_DATE)'), '>=', 'tweb_penduduk_umur.dari')
                    ->on(DB::raw('TIMESTAMPDIFF(YEAR, tweb_penduduk.tanggallahir, CURRENT_DATE)'), '<=', 'tweb_penduduk_umur.sampai');
            })
                ->where('tweb_penduduk.config_id', '=', $this->configId)
                ->whereBetween('tweb_penduduk_umur.id', [6, 37])
                ->groupBy('tweb_penduduk_umur.id', 'tweb_penduduk_umur.nama')
                ->orderBy('tweb_penduduk_umur.nama', 'asc')
                ->select('tweb_penduduk_umur.id as id', DB::raw('COALESCE(tweb_penduduk_umur.nama, "Belum Terdata") AS nama'), DB::raw('COUNT(*) as total'))
                ->get();

            $this->dispatch('column', data:$this->data);
    }

    public function render()
    {
        return view('livewire.front.statistik');
    }
}
