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


    public function umur($id_referensi, $tabel_referensi, $judul, $select, $where)
    {
        $this->judul = $judul;
        $this->data = DB::select("
        SELECT 
            $select,
            (SELECT COUNT(b.id)
             FROM penduduk_hidup b
             LEFT JOIN tweb_wil_clusterdesa a ON b.id_cluster = a.id
             WHERE $where) as total,
            (SELECT COUNT(b.id)
             FROM penduduk_hidup b
             LEFT JOIN tweb_wil_clusterdesa a ON b.id_cluster = a.id
             WHERE $where
             AND b.sex = '1') as laki,
            (SELECT COUNT(b.id)
             FROM penduduk_hidup b
             LEFT JOIN tweb_wil_clusterdesa a ON b.id_cluster = a.id
             WHERE $where
             AND b.sex = '2') as perempuan
        FROM
            $tabel_referensi u
        WHERE
            u.status = $id_referensi
        GROUP BY u.id;
    ");

        $datastring = json_decode(json_encode($this->data), true);
        // Calculate totals
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
        foreach ($this->data_jml_penduduk_hidup() as $data) {
            $this->menghitungBarisBelum($data);
        }

        $this->dispatch('column', data: $this->data);
    }
    public function ktp($tabel_referensi, $judul, $select, $where)
    {
        $this->judul = $judul;
        $this->data = DB::select("
        SELECT 
            $select,
            (SELECT COUNT(b.id)
             FROM penduduk_hidup b
             LEFT JOIN tweb_wil_clusterdesa a ON b.id_cluster = a.id
             WHERE $where) as total,
            (SELECT COUNT(b.id)
             FROM penduduk_hidup b
             LEFT JOIN tweb_wil_clusterdesa a ON b.id_cluster = a.id
             WHERE $where
             AND b.sex = '1') as laki,
            (SELECT COUNT(b.id)
             FROM penduduk_hidup b
             LEFT JOIN tweb_wil_clusterdesa a ON b.id_cluster = a.id
             WHERE $where
             AND b.sex = '2') as perempuan
        FROM
            $tabel_referensi u
        GROUP BY u.id;
    ");

        $datastring = json_decode(json_encode($this->data), true);
        // Calculate totals
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
        foreach ($this->data_jml_penduduk_hidup() as $data) {
            $this->menghitungBarisBelum($data);
        }

        $this->dispatch('column', data: $this->data);
    }
    public function covid($tabel_referensi, $judul)
    {
        $this->judul = $judul;
        $this->data = DB::select("
        SELECT 
        u.*, COUNT(k.id) as total,
        COUNT(CASE WHEN k.status_covid = u.id AND p.sex = 1 THEN k.id_terdata END) AS laki,
        COUNT(CASE WHEN k.status_covid = u.id AND p.sex = 2 THEN k.id_terdata END) AS perempuan
        FROM $tabel_referensi u
        LEFT JOIN covid19_pemudik k ON k.status_covid = u.id AND k.config_id = {$this->configId}
        LEFT JOIN tweb_penduduk p ON p.id=k.id_terdata
        GROUP BY u.id;");

        $datastring = json_decode(json_encode($this->data), true);
        // Calculate totals
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
        foreach ($this->data_jml_penduduk_hidup() as $data) {
            $this->menghitungBarisBelum($data);
        }

        $this->dispatch('column', data: $this->data);
    }
    public function suku($judul)
    {
        $this->judul = $judul;
        $this->data = DB::select("
        SELECT u.suku AS id,
            u.suku AS nama,  
            COUNT(u.sex) AS total,
            COUNT(CASE WHEN u.sex = 1 THEN 1 END) AS laki,
            COUNT(CASE WHEN u.sex = 2 THEN 1 END) AS perempuan
        FROM 
            penduduk_hidup u
        LEFT JOIN 
            tweb_wil_clusterdesa a ON u.id_cluster = a.id
        WHERE 
            u.suku IS NOT NULL AND u.suku != ''
        GROUP BY 
            u.suku;
        ;");

        $datastring = json_decode(json_encode($this->data), true);
        // Calculate totals
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
        foreach ($this->data_jml_penduduk_hidup() as $data) {
            $this->menghitungBarisBelum($data);
        }

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

        $this->statistik(14);
    }

    public function statistik($id)
    {
        $statistik_penduduk = [
            '0'     => ['id_referensi' => 'pendidikan_kk_id', 'tabel_referensi' => 'tweb_penduduk_pendidikan_kk', 'judul' => 'Pendidikan Dalam KK'],
            '1'     => ['id_referensi' => 'pekerjaan_id', 'tabel_referensi' => 'tweb_penduduk_pekerjaan', 'judul' => 'Pekerjaan'],
            '2'     => ['id_referensi' => 'status_kawin', 'tabel_referensi' => 'tweb_penduduk_kawin', 'judul' => 'Status Perkawinan'],
            '3'     => ['id_referensi' => 'agama_id', 'tabel_referensi' => 'tweb_penduduk_agama', 'judul' => 'Agama'],
            '4'     => ['id_referensi' => 'sex', 'tabel_referensi' => 'tweb_penduduk_sex', 'judul' => 'Jenis Kelamin'],
            '5'     => ['id_referensi' => 'warganegara_id', 'tabel_referensi' => 'tweb_penduduk_warganegara', 'judul' => 'Warga Negara'],
            '6'     => ['id_referensi' => 'status', 'tabel_referensi' => 'tweb_penduduk_status', 'judul' => 'Status Penduduk'],
            '7'     => ['id_referensi' => 'golongan_darah_id', 'tabel_referensi' => 'tweb_golongan_darah', 'judul' => 'Golongan Darah'],
            '8'     => ['id_referensi' => 'cacat_id', 'tabel_referensi' => 'tweb_cacat', 'judul' => 'Penyandang Cacat'],
            '9'     => ['id_referensi' => 'sakit_menahun_id', 'tabel_referensi' => 'tweb_sakit_menahun', 'judul' => 'Sakit Menahun'],
            '10'    => ['id_referensi' => 'pendidikan_sedang_id', 'tabel_referensi' => 'tweb_penduduk_pendidikan',  'judul' => 'Pendidikan Sedang Di Tempuh'],
            '11'    => ['id_referensi' => 'cara_kb_id', 'tabel_referensi' => 'tweb_cara_kb', 'judul' => 'Akseptor KB'],
            '12'    => ['id_referensi' => 'id_asuransi', 'tabel_referensi' => 'tweb_penduduk_asuransi', 'judul' => 'Asuransi Kesehatan'],
            '13'    => ['id_referensi' => 'kk_level', 'tabel_referensi' => 'tweb_penduduk_hubungan', 'judul' => 'Hubungan KK'],
            '14'    => ['id_referensi' => '1', 'tabel_referensi' => 'tweb_penduduk_umur', 'judul' => 'Rentang Umur'],
            '15'    => ['id_referensi' => '0', 'tabel_referensi' => 'tweb_penduduk_umur', 'judul' => 'Kategori Umur'],
            '16'    => ['id_referensi' => '1', 'tabel_referensi' => 'tweb_penduduk_umur', 'judul' => 'Akta Kelahiran'],
            '17'    => ['id_referensi' => '', 'tabel_referensi' => 'tweb_status_ktp', 'judul' => 'Kepemilikan KTP'],
            '18'    => ['id_referensi' => '', 'tabel_referensi' => 'ref_status_covid', 'judul' => 'Status Covid'],
            '19'    => ['id_referensi' => '', 'tabel_referensi' => '', 'judul' => 'SUKU / ETNIS'],
            '20'    => ['id_referensi' => 'pekerjaan_id', 'tabel_referensi' => 'tweb_penduduk_pekerjaan', 'judul' => 'BPJS Ketenagakerjaan'],
            '21'    => ['id_referensi' => 'hamil', 'tabel_referensi' => 'ref_penduduk_hamil', 'judul' => 'Status Kehamilan'],
            '22'    => ['id_referensi' => '', 'tabel_referensi' => 'program', 'judul' => 'Bantuan Penduduk'],

        ];

        if (array_key_exists($id, $statistik_penduduk)) {
            if ($id == 14) {
                $select = "u.*";
                $where = "(DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(tanggallahir)), '%Y')+0) >= u.dari AND (DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(tanggallahir)), '%Y')+0) <= u.sampai AND b.config_id = {$this->configId}";
                $this->umur($statistik_penduduk[$id]['id_referensi'], $statistik_penduduk[$id]['tabel_referensi'], $statistik_penduduk[$id]['judul'], $select, $where);
            } else if ($id == 15) {
                $select = "u.*, concat(u.nama, ' (', u.dari, ' - ', u.sampai, ')') as nama";
                $where = "(DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(tanggallahir)), '%Y')+0) >= u.dari AND (DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(tanggallahir)), '%Y')+0) <= u.sampai AND b.config_id = {$this->configId}";
                $this->umur($statistik_penduduk[$id]['id_referensi'], $statistik_penduduk[$id]['tabel_referensi'], $statistik_penduduk[$id]['judul'], $select, $where);
            } else if ($id == 16) {
                $select = "u.*, concat('UMUR ', u.dari, ' S/D ', u.sampai, ' TAHUN') as nama";
                $where = "(DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(tanggallahir)), '%Y')+0) >= u.dari AND (DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(tanggallahir)), '%Y')+0) <= u.sampai AND akta_lahir <> '' AND b.config_id = {$this->configId}";
                $this->umur($statistik_penduduk[$id]['id_referensi'], $statistik_penduduk[$id]['tabel_referensi'], $statistik_penduduk[$id]['judul'], $select, $where);
            } else if ($id == 17) {
                $select = "u.*";
                $where = "((DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(tanggallahir)), '%Y')+0)>=17 OR (status_kawin IS NOT NULL AND status_kawin <> 1)) AND u.status_rekam = status_rekam AND b.config_id = {$this->configId}";
                $this->ktp($statistik_penduduk[$id]['tabel_referensi'], $statistik_penduduk[$id]['judul'], $select, $where);
            } else if ($id == 18) {
                $this->covid($statistik_penduduk[$id]['tabel_referensi'], $statistik_penduduk[$id]['judul']);
            } else if ($id == 19) {
                $this->suku($statistik_penduduk[$id]['judul']);
            } else if ($id == 20) {
                $where = "p.bpjs_ketenagakerjaan IS NOT NULL AND p.bpjs_ketenagakerjaan != ''";
                $this->select_jml_penduduk_per_kategori_costum($statistik_penduduk[$id]['id_referensi'], $statistik_penduduk[$id]['tabel_referensi'], $statistik_penduduk[$id]['judul'], $where);
            } else if ($id == 21) {
                $where = "p.sex = 2";
                $this->select_jml_penduduk_per_kategori_costum($statistik_penduduk[$id]['id_referensi'], $statistik_penduduk[$id]['tabel_referensi'], $statistik_penduduk[$id]['judul'], $where);
            } else if ($id == 22) {
                $where = "(u.config_id = {$this->configId} OR u.config_id IS NULL)";
                $this->bantuan($statistik_penduduk[$id]['tabel_referensi'], $statistik_penduduk[$id]['judul'], $where);
            } else {
                $this->select_jml_penduduk_per_kategori($statistik_penduduk[$id]['id_referensi'], $statistik_penduduk[$id]['tabel_referensi'], $statistik_penduduk[$id]['judul']);
            }
        } else {
            $this->kelasSosial();
        }
    }

    public function bantuan($tabel_referensi, $judul, $where)
    {
        $this->judul = $judul;
        $this->data = DB::select("SELECT 
        u.*,
        (SELECT COUNT(kartu_nik) FROM program_peserta k WHERE k.program_id = u.id AND k.config_id = u.config_id) AS total,
        (SELECT COUNT(k.kartu_nik) FROM program_peserta k INNER JOIN tweb_penduduk p ON k.kartu_nik = p.nik WHERE k.program_id = u.id AND p.sex = 1 AND k.config_id = u.config_id) AS laki,
        (SELECT COUNT(k.kartu_nik) FROM program_peserta k INNER JOIN tweb_penduduk p ON k.kartu_nik = p.nik WHERE k.program_id = u.id AND p.sex = 2 AND k.config_id = u.config_id) AS perempuan
    FROM 
        $tabel_referensi u 
    WHERE 
        $where");
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
    public function select_jml_penduduk_per_kategori($id_referensi, $tabel_referensi, $judul)
    {
        $this->judul = $judul;
        $this->data = DB::select("SELECT 
                            u.*,
                            COUNT(p.id) AS total,
                            COUNT(CASE WHEN p.sex = 1 THEN p.id END) AS laki,
                            COUNT(CASE WHEN p.sex = 2 THEN p.id END) AS perempuan
                        FROM 
                            $tabel_referensi u
                        LEFT JOIN 
                            penduduk_hidup p ON u.id = p.$id_referensi AND p.config_id = $this->configId
                        LEFT JOIN 
                            tweb_wil_clusterdesa a ON p.id_cluster = a.id
                        GROUP BY 
                            u.id");
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
    public function select_jml_penduduk_per_kategori_costum($id_referensi, $tabel_referensi, $judul, $where)
    {
        $this->judul = $judul;
        $this->data = DB::select("
        SELECT 
            u.*,
            COUNT(p.id) AS total,
            COUNT(CASE WHEN p.sex = 1 THEN p.id END) AS laki,
            COUNT(CASE WHEN p.sex = 2 THEN p.id END) AS perempuan
        FROM 
            $tabel_referensi u
        LEFT JOIN 
            penduduk_hidup p ON u.id = p.$id_referensi AND p.config_id = $this->configId
        LEFT JOIN 
            tweb_wil_clusterdesa a ON p.id_cluster = a.id
        WHERE 
            $where
        GROUP BY 
            u.id
    ");
        $datastring = json_decode(json_encode($this->data), true);
        $this->jumlah = 0;
        $this->totallaki = 0;
        $this->totalperem = 0;
        foreach ($datastring as $row) {
            $this->jumlah += $row['total'];
            $this->totallaki += $row['laki'];
            $this->totalperem += $row['perempuan'];
        }
        foreach ($this->data_jml_penduduk_hidup() as $data) {
            $this->menghitungBarisTotal($data);
        }
        foreach ($this->data_jml_penduduk_hidup() as $data) {
            $this->menghitungBarisBelum($data);
        }
        $this->dispatch('column', data: $this->data);
    }
    public function render()
    {
        return view('livewire.front.statistik');
    }
}
