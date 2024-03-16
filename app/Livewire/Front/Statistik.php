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
    public $selectKecamatan, $selectDesa, $listDesa, $show, $jenis = '', $configId, $data, $jumlah, $totallaki, $totalperem, $cari, $judul, $baris_belum = [], $baris_total = [], $baris_persen_belum = [];
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
        $this->data = DB::select("SELECT u.nama,
        ((SELECT COUNT(b.id)
          FROM penduduk_hidup b
          LEFT JOIN tweb_wil_clusterdesa a ON b.id_cluster = a.id
          WHERE (DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(tanggallahir)), '%Y')+0) >= u.dari
          AND (DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(tanggallahir)), '%Y')+0) <= u.sampai)) as total,
        ((SELECT COUNT(b.id)
          FROM penduduk_hidup b
          LEFT JOIN tweb_wil_clusterdesa a ON b.id_cluster = a.id
          WHERE (DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(tanggallahir)), '%Y')+0) >= u.dari
          AND (DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(tanggallahir)), '%Y')+0) <= u.sampai
          AND b.sex = '1')) as laki,
        ((SELECT COUNT(b.id)
          FROM penduduk_hidup b
          LEFT JOIN tweb_wil_clusterdesa a ON b.id_cluster = a.id
          WHERE (DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(tanggallahir)), '%Y')+0) >= u.dari
          AND (DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(tanggallahir)), '%Y')+0) <= u.sampai
          AND b.sex = '2')) as perempuan
    FROM
        tweb_penduduk_umur u
    WHERE
        u.status = '1'
         GROUP BY u.id;");
        $datastring = json_decode(json_encode($this->data), true);
        $this->jumlah = 0;
        $this->totallaki = 0;
        $this->totalperem = 0;
        //menghitung jumlah
        foreach ($datastring as $row) {
            $this->jumlah += $row['total'];
            $this->totallaki += $row['laki'];
            $this->totalperem += $row['perempuan'];
        }
        foreach ($this->data_jml_penduduk_hidup() as $data) {
            $this->menghitungBarisTotal($data);
        }
        //menghitung sisa 
        foreach ($this->data_jml_penduduk_hidup() as $data) {
            $this->menghitungBarisBelum($data);
        }
        $this->dispatch('column', data: $this->data);
    }
    public function kategoriUmur()
    {
        $this->jenis = "kategoriUmur";
        $this->judul = 'Kategori Umur';
        $this->data = DB::select("SELECT u.nama,
        ((SELECT COUNT(b.id)
          FROM penduduk_hidup b
          LEFT JOIN tweb_wil_clusterdesa a ON b.id_cluster = a.id
          WHERE (DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(tanggallahir)), '%Y')+0) >= u.dari
          AND (DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(tanggallahir)), '%Y')+0) <= u.sampai)) as total,
        ((SELECT COUNT(b.id)
          FROM penduduk_hidup b
          LEFT JOIN tweb_wil_clusterdesa a ON b.id_cluster = a.id
          WHERE (DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(tanggallahir)), '%Y')+0) >= u.dari
          AND (DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(tanggallahir)), '%Y')+0) <= u.sampai
          AND b.sex = '1')) as laki,
        ((SELECT COUNT(b.id)
          FROM penduduk_hidup b
          LEFT JOIN tweb_wil_clusterdesa a ON b.id_cluster = a.id
          WHERE (DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(tanggallahir)), '%Y')+0) >= u.dari
          AND (DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(tanggallahir)), '%Y')+0) <= u.sampai
          AND b.sex = '2')) as perempuan
    FROM
        tweb_penduduk_umur u
    WHERE
        u.status = '0'
         GROUP BY u.id;");
        $datastring = json_decode(json_encode($this->data), true);
        $this->jumlah = 0;
        $this->totallaki = 0;
        $this->totalperem = 0;
        //menghitung jumlah
        foreach ($datastring as $row) {
            $this->jumlah += $row['total'];
            $this->totallaki += $row['laki'];
            $this->totalperem += $row['perempuan'];
        }
        foreach ($this->data_jml_penduduk_hidup() as $data) {
            $this->menghitungBarisTotal($data);
        }
        //menghitung sisa 
        foreach ($this->data_jml_penduduk_hidup() as $data) {
            $this->menghitungBarisBelum($data);
        }
        $this->dispatch('column', data: $this->data);
    }
    public function pendidikanKk()
    {
        $this->jenis = "pendidikan_kk";
        $this->judul = 'Pendidikan Dalam KK';
        $this->data = DB::select('SELECT 
                        u.*,
                        COUNT(p.id) AS total,
                        COUNT(CASE WHEN p.sex = 1 THEN p.id END) AS laki,
                        COUNT(CASE WHEN p.sex = 2 THEN p.id END) AS perempuan
                    FROM 
                        tweb_penduduk_pendidikan_kk u
                    LEFT JOIN 
                        penduduk_hidup p ON u.id = p.pendidikan_kk_id AND p.config_id = 1
                    LEFT JOIN 
                        tweb_wil_clusterdesa a ON p.id_cluster = a.id
                    GROUP BY 
                        u.id;');
        $datastring = json_decode(json_encode($this->data), true);
        $this->jumlah = 0;
        $this->totallaki = 0;
        $this->totalperem = 0;
        //menghitung jumlah
        foreach ($datastring as $row) {
            $this->jumlah += $row['total'];
            $this->totallaki += $row['laki'];
            $this->totalperem += $row['perempuan'];
        }
        foreach ($this->data_jml_penduduk_hidup() as $data) {
            $this->menghitungBarisTotal($data);
        }
        //menghitung sisa 
        foreach ($this->data_jml_penduduk_hidup() as $data) {
            $this->menghitungBarisBelum($data);
        }
        $this->dispatch('column', data: $this->data);
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
        $this->dispatch('column', data: $this->data);
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
        $this->dispatch('column', data: $this->data);
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
        $this->dispatch('column', data: $this->data);
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
        $this->dispatch('column', data: $this->data);
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
        $this->dispatch('column', data: $this->data);
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
        $this->dispatch('column', data: $this->data);
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
        $this->dispatch('column', data: $this->data);
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
        $this->dispatch('column', data: $this->data);
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
        $this->dispatch('column', data: $this->data);
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
        $this->dispatch('column', data: $this->data);
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
        $this->dispatch('column', data: $this->data);
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
        $this->dispatch('column', data: $this->data);
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
        $this->dispatch('column', data: $this->data);
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
        $this->dispatch('column', data: $this->data);
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
        $value = Cache::remember("suku-" . $this->configId, 60 * 60, function () {
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


        $this->dispatch('column', data: $this->data);
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
    public function data_jml_semua_penduduk()
    {
        $semua = DB::select('SELECT 
                COUNT(k.id) as jumlah,
                COUNT(CASE WHEN p.sex = 1 THEN p.id END) AS laki,
                COUNT(CASE WHEN p.sex = 2 THEN p.id END) AS perempuan
            FROM keluarga_aktif k
            LEFT JOIN tweb_penduduk p ON p.id = k.nik_kepala
            LEFT JOIN tweb_wil_clusterdesa a ON p.id_cluster = a.id;');
        return $semua;
    }

    public function data_jml_penduduk_hidup()
    {
        $semua = DB::select('SELECT COUNT(`tweb_penduduk`.`id`) AS jumlah,
            COUNT(CASE WHEN `tweb_penduduk`.`sex` = 1 THEN `tweb_penduduk`.`id` END) AS laki,
            COUNT(CASE WHEN `tweb_penduduk`.`sex` = 2 THEN `tweb_penduduk`.`id` END) AS perempuan
        FROM `tweb_penduduk` 
        LEFT JOIN `tweb_wil_clusterdesa` AS a ON `tweb_penduduk`.`id_cluster` = `a`.`id` 
        WHERE `tweb_penduduk`.`status_dasar` = 1;');
        return $semua;
    }
    public function kelasSosial()
    {
        $this->judul = 'kelas Sosial';
        $this->data = DB::select('
                            SELECT u.*, 
                        COUNT(CASE WHEN k.kelas_sosial = u.id AND p.sex = 1 THEN p.id END) + COUNT(CASE WHEN k.kelas_sosial = u.id AND p.sex = 2 THEN p.id END) AS total,
                        COUNT(CASE WHEN k.kelas_sosial = u.id AND p.sex = 1 THEN p.id END) AS laki,
                        COUNT(CASE WHEN k.kelas_sosial = u.id AND p.sex = 2 THEN p.id END) AS perempuan
                    FROM tweb_keluarga_sejahtera AS u
                    LEFT JOIN tweb_keluarga AS k ON k.kelas_sosial = u.id
                    LEFT JOIN tweb_penduduk AS p ON k.nik_kepala = p.id AND p.status_dasar = 1
                    WHERE k.config_id = 1
                    GROUP BY u.id
                    ORDER BY u.id ASC;');

        $datastring = json_decode(json_encode($this->data), true);
        $this->jumlah = 0;
        $this->totallaki = 0;
        $this->totalperem = 0;
        //menghitung jumlah
        foreach ($datastring as $row) {
            $this->jumlah += $row['total'];
            $this->totallaki += $row['laki'];
            $this->totalperem += $row['perempuan'];
        }
        //menghitung semua total
        foreach ($this->data_jml_semua_penduduk() as $data) {
            $this->menghitungBarisTotal($data);
        }

        //menghitung sisa 
        foreach ($this->data_jml_semua_penduduk() as $data) {
            $this->menghitungBarisBelum($data);
        }
        $this->dispatch('column', data: $this->data);
    }

    public function menghitungBarisTotal($data)
    {
        $this->baris_total['jumlah'] = $data->jumlah;
        $this->baris_total['laki'] = $data->laki;
        $this->baris_total['cewe'] = $data->perempuan;
    }

    public function menghitungBarisBelum($data)
    {
        $this->baris_belum['jumlah'] = $data->jumlah - $this->jumlah;
        $this->baris_belum['laki'] = $data->laki - $this->totallaki;
        $this->baris_belum['cewe'] = $data->perempuan - $this->totalperem;

        $this->baris_persen_belum['jumlah'] = number_format($this->baris_belum['jumlah'] / $data->jumlah * 100, 2);
        $this->baris_persen_belum['laki'] = number_format($this->baris_belum['laki'] / $data->jumlah * 100, 2);
        $this->baris_persen_belum['cewe'] = number_format($this->baris_belum['cewe'] / $data->jumlah * 100, 2);
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
            $this->configId = $this->cari->id;
        }
        $this->totalPendudukDesa = TwebPenduduk::where('config_id', '=', $this->configId)->count();
        $this->totalKeluargaDesa = TwebKeluarga::where('config_id', '=', $this->configId)->count();
        $this->rtmDesa = TwebRtm::where('config_id', '=', $this->configId)->count();
        $this->bantuanDesa =  Program::where('config_id', '=', $this->configId)->count();

        $this->kelasSosial();
    }

    public function render()
    {
        return view('livewire.front.statistik');
    }
}
