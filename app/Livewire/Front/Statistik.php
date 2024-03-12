<?php

namespace App\Livewire\Front;

use App\Livewire\Front\Chart\ColumnChart;
use App\Models\Config;
use App\Models\TwebPenduduk;
use App\Models\TwebPendudukPendidikanKk;
use App\Models\TwebPendudukUmur;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;
use Asantibanez\LivewireCharts\Models\PieChartModel;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Statistik extends Component
{
    public $selectKecamatan, $selectDesa, $listDesa, $show, $jenis = '', $configId, $data;
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
    }
    public function kategoriUmur()
    {
        $this->jenis = "kategoriUmur";
    }
    public function pendidikanKk()
    {
        $this->jenis = "pendidikan_kk";
    }

    public function pendidikanDitempuh()
    {
        $this->jenis = "pendidikan";
    }
    public function pekerjaan()
    {
        $this->jenis = "pekerjaan";
    }
    public function statusPerkawinan()
    {
        $this->jenis = "statusPerkawinan";
    }
    public function agama()
    {
        $this->jenis = "agama";
    }
    public function jenisKelamin()
    {
        $this->jenis = "jenisKelamin";
    }
    public function hubunganKk()
    {
        $this->jenis = "hubunganKk";
    }
    public function wargaNegara()
    {
        $this->jenis = "wargaNegara";
    }
    public function statusPenduduk()
    {
        $this->jenis = "statusPenduduk";
    }
    public function golonganDarah()
    {
        $this->jenis = "golonganDarah";
    }
    public function penyandangCacat()
    {
        $this->jenis = "penyandangCacat";
    }
    public function penyakitMenahun()
    {
        $this->jenis = "penyakitMenahun";
    }
    public function akseptorKB()
    {
        $this->jenis = "akseptorKB";
    }
    public function kepemilikanKtp()
    {
        $this->jenis = "kepemilikanKtp";
    }
    public function asuransiKesehatan()
    {
        $this->jenis = "asuransiKesehatan";
    }
    public function sukuEtnis()
    {
        $this->jenis = "sukuEtnis";
    }
    public function tampilkan()
    {
        $rules = [
            'selectKecamatan' => 'required',
            'selectDesa' => 'required'
        ];
        $this->validate($rules);

        $cari = Config::find($this->selectDesa);
        if (isset($cari)) {
            $this->show = true;
            $this->jenis = "rentangUmur";
            $this->configId = $cari->id;
        }
    }

    public function render()
    {
        if ($this->jenis == "rentangUmur") {
            $result = TwebPenduduk::join('tweb_penduduk_umur', function ($join) {
                $join->on(DB::raw('TIMESTAMPDIFF(YEAR, tweb_penduduk.tanggallahir, CURRENT_DATE)'), '>=', 'tweb_penduduk_umur.dari')
                    ->on(DB::raw('TIMESTAMPDIFF(YEAR, tweb_penduduk.tanggallahir, CURRENT_DATE)'), '<=', 'tweb_penduduk_umur.sampai');
            })
                ->where('tweb_penduduk.config_id', '=', $this->configId)
                ->whereBetween('tweb_penduduk_umur.id', [6, 37])
                ->groupBy('tweb_penduduk_umur.id', 'tweb_penduduk_umur.nama')
                ->orderBy('tweb_penduduk_umur.nama', 'asc')
                ->select('tweb_penduduk_umur.id as id', DB::raw('COALESCE(tweb_penduduk_umur.nama, "Belum Terdata") AS nama'), DB::raw('COUNT(*) as total'))
                ->get();

            $this->data = $result;
            $columnChartModel = $result
                ->reduce(
                    function (ColumnChartModel $columnChartModel, $data) {
                        $id = $data->id;
                        $nama = $data->nama;
                        $value = $data->total;
                        $warna[$data->id] = '#' . dechex(rand(0x000000, 0xFFFFFF));
                        return $columnChartModel->addColumn($nama, $value, $warna[$id]);
                    },
                    (new ColumnChartModel())
                        ->setTitle('Rentang Umur')
                        ->setAnimated($this->firstRun)
                        ->withOnColumnClickEventName('onColumnClick')
                );

            $pieChartModel = $result
                ->reduce(
                    function (PieChartModel $pieChartModel, $data) {
                        $id = $data->id;
                        $nama = $data->nama;
                        $value = $data->total;
                        $warna[$data->id] = '#' . dechex(rand(0x000000, 0xFFFFFF));

                        return $pieChartModel->addSlice($nama, $value, $warna[$id]);
                    },
                    (new PieChartModel())
                        ->setTitle('Rentang Umur')
                        ->setAnimated($this->firstRun)
                        ->withDataLabels()
                        ->withOnSliceClickEvent('onSliceClick')
                );
        }
        if ($this->jenis == "kategoriUmur") {
            $result = TwebPenduduk::join('tweb_penduduk_umur', function ($join) {
                $join->on(DB::raw('TIMESTAMPDIFF(YEAR, tweb_penduduk.tanggallahir, CURRENT_DATE)'), '>=', 'tweb_penduduk_umur.dari')
                    ->on(DB::raw('TIMESTAMPDIFF(YEAR, tweb_penduduk.tanggallahir, CURRENT_DATE)'), '<=', 'tweb_penduduk_umur.sampai');
            })
                ->where('tweb_penduduk.config_id', '=', $this->configId)
                ->whereBetween('tweb_penduduk_umur.id', [1, 4])
                ->groupBy('tweb_penduduk_umur.id', 'tweb_penduduk_umur.nama')
                ->orderBy('tweb_penduduk_umur.nama', 'asc')
                ->select('tweb_penduduk_umur.id as id', DB::raw('COALESCE(tweb_penduduk_umur.nama, "Belum Terdata") AS nama'), DB::raw('COUNT(*) as total'))
                ->get();

            $this->data = $result;
            $columnChartModel = $result
                ->reduce(
                    function (ColumnChartModel $columnChartModel, $data) {
                        $id = $data->id;
                        $nama = $data->nama;
                        $value = $data->total;
                        $warna[$data->id] = '#' . dechex(rand(0x000000, 0xFFFFFF));
                        return $columnChartModel->addColumn($nama, $value, $warna[$id]);
                    },
                    (new ColumnChartModel())
                        ->setTitle('Kategori Umur')
                        ->setAnimated($this->firstRun)
                        ->withOnColumnClickEventName('onColumnClick')
                );

            $pieChartModel = $result
                ->reduce(
                    function (PieChartModel $pieChartModel, $data) {
                        $id = $data->id;
                        $nama = $data->nama;
                        $value = $data->total;
                        $warna[$data->id] = '#' . dechex(rand(0x000000, 0xFFFFFF));

                        return $pieChartModel->addSlice($nama, $value, $warna[$id]);
                    },
                    (new PieChartModel())
                        ->setTitle('Kategori Umur')
                        ->setAnimated($this->firstRun)
                        ->withDataLabels()
                        ->withOnSliceClickEvent('onSliceClick')
                );
        }
        if ($this->jenis == "pendidikan_kk") {
            $result = DB::table('tweb_penduduk')
                ->leftjoin('tweb_penduduk_pendidikan_kk', 'tweb_penduduk.pendidikan_kk_id', '=', 'tweb_penduduk_pendidikan_kk.id')
                ->select(
                    'tweb_penduduk_pendidikan_kk.id',
                    DB::raw('COALESCE(tweb_penduduk_pendidikan_kk.nama, "Belum Terdata") AS nama'),
                    DB::raw('COUNT(tweb_penduduk_pendidikan_kk.id) AS total')
                )
                ->where('tweb_penduduk.config_id', '=', $this->configId)
                ->groupBy('tweb_penduduk_pendidikan_kk.id')
                ->orderBy('tweb_penduduk_pendidikan_kk.id', 'asc')->get();

            $this->data = $result;
            $columnChartModel = $result
                ->reduce(
                    function (ColumnChartModel $columnChartModel, $data) {
                        $id = $data->id;
                        $nama = $data->nama;
                        $value = $data->total;
                        $warna[$data->id] = '#' . dechex(rand(0x000000, 0xFFFFFF));
                        return $columnChartModel->addColumn($nama, $value, $warna[$id]);
                    },
                    (new ColumnChartModel())
                        ->setTitle('Pendidikan Dalam Keluarga')
                        ->setAnimated($this->firstRun)
                        ->withOnColumnClickEventName('onColumnClick')
                );

            $pieChartModel = $result
                ->reduce(
                    function (PieChartModel $pieChartModel, $data) {
                        $id = $data->id;
                        $nama = $data->nama;
                        $value = $data->total;
                        $warna[$data->id] = '#' . dechex(rand(0x000000, 0xFFFFFF));

                        return $pieChartModel->addSlice($nama, $value, $warna[$id]);
                    },
                    (new PieChartModel())
                        ->setTitle('Pendidikan Dalam Keluarga')
                        ->setAnimated($this->firstRun)
                        ->withDataLabels()
                        ->withOnSliceClickEvent('onSliceClick')
                );
        } elseif ($this->jenis == "pendidikan") {
            $result = DB::table('tweb_penduduk')
                ->leftJoin('tweb_penduduk_pendidikan', 'tweb_penduduk.pendidikan_sedang_id', '=', 'tweb_penduduk_pendidikan.id')
                ->select(
                    'tweb_penduduk_pendidikan.id',
                    DB::raw('COALESCE(tweb_penduduk_pendidikan.nama, "Belum Terdata") AS nama'),
                    DB::raw('COUNT(tweb_penduduk.id) AS total')
                )
                ->groupBy('tweb_penduduk_pendidikan.id', 'nama')
                ->orderBy('tweb_penduduk_pendidikan.id', 'asc')
                ->get();

            $this->data = $result;
            $columnChartModel = $result
                ->reduce(
                    function (ColumnChartModel $columnChartModel, $data) {
                        $id = $data->id;
                        $nama = $data->nama;
                        $value = $data->total;
                        $warna[$data->id] = '#' . dechex(rand(0x000000, 0xFFFFFF));
                        return $columnChartModel->addColumn($nama, $value, $warna[$id]);
                    },
                    (new ColumnChartModel())
                        ->setTitle('Pendidikan Sedang Ditempuh')
                        ->setAnimated($this->firstRun)
                        ->withOnColumnClickEventName('onColumnClick')
                );

            $pieChartModel = $result
                ->reduce(
                    function (PieChartModel $pieChartModel, $data) {
                        $id = $data->id;
                        $nama = $data->nama;
                        $value = $data->total;
                        $warna[$data->id] = '#' . dechex(rand(0x000000, 0xFFFFFF));

                        return $pieChartModel->addSlice($nama, $value, $warna[$id]);
                    },
                    (new PieChartModel())
                        ->setTitle('Pendidikan Sedang Ditempuh')
                        ->setAnimated($this->firstRun)
                        ->withDataLabels()
                        ->withOnSliceClickEvent('onSliceClick')
                );
        } elseif ($this->jenis == "statusPerkawinan") {
            $result = DB::table('tweb_penduduk')
                ->leftJoin('tweb_penduduk_kawin', 'tweb_penduduk.status_kawin', '=', 'tweb_penduduk_kawin.id')
                ->select(
                    'tweb_penduduk_kawin.id',
                    DB::raw('COALESCE(tweb_penduduk_kawin.nama, "Belum Terdata") AS nama'),
                    DB::raw('COUNT(tweb_penduduk.id) AS total')
                )
                ->groupBy('tweb_penduduk_kawin.id', 'nama')
                ->orderBy('tweb_penduduk_kawin.id', 'asc')
                ->get();

            $this->data = $result;
            $columnChartModel = $result
                ->reduce(
                    function (ColumnChartModel $columnChartModel, $data) {
                        $id = $data->id;
                        $nama = $data->nama;
                        $value = $data->total;
                        $warna[$data->id] = '#' . dechex(rand(0x000000, 0xFFFFFF));
                        return $columnChartModel->addColumn($nama, $value, $warna[$id]);
                    },
                    (new ColumnChartModel())
                        ->setTitle('Status Perkawinan')
                        ->setAnimated($this->firstRun)
                        ->withOnColumnClickEventName('onColumnClick')
                );

            $pieChartModel = $result
                ->reduce(
                    function (PieChartModel $pieChartModel, $data) {
                        $id = $data->id;
                        $nama = $data->nama;
                        $value = $data->total;
                        $warna[$data->id] = '#' . dechex(rand(0x000000, 0xFFFFFF));

                        return $pieChartModel->addSlice($nama, $value, $warna[$id]);
                    },
                    (new PieChartModel())
                        ->setTitle('Status Perkawinan')
                        ->setAnimated($this->firstRun)
                        ->withDataLabels()
                        ->withOnSliceClickEvent('onSliceClick')
                );
        } elseif ($this->jenis == "pekerjaan") {
            $result = DB::table('tweb_penduduk')
                ->leftJoin('tweb_penduduk_pekerjaan', 'tweb_penduduk.pekerjaan_id', '=', 'tweb_penduduk_pekerjaan.id')
                ->select(
                    'tweb_penduduk_pekerjaan.id',
                    DB::raw('COALESCE(tweb_penduduk_pekerjaan.nama, "Belum Terdata") AS nama'),
                    DB::raw('COUNT(tweb_penduduk.id) AS total')
                )
                ->groupBy('tweb_penduduk_pekerjaan.id', 'nama')
                ->orderBy('tweb_penduduk_pekerjaan.id', 'asc')->get();

            $this->data = $result;
            $columnChartModel = $result
                ->reduce(
                    function (ColumnChartModel $columnChartModel, $data) {
                        $id = $data->id;
                        $nama = $data->nama;
                        $value = $data->total;
                        $warna[$data->id] = '#' . dechex(rand(0x000000, 0xFFFFFF));
                        return $columnChartModel->addColumn($nama, $value, $warna[$id]);
                    },
                    (new ColumnChartModel())
                        ->setTitle('Pekerjaan')
                        ->setAnimated($this->firstRun)
                        ->withOnColumnClickEventName('onColumnClick')
                );

            $pieChartModel = $result
                ->reduce(
                    function (PieChartModel $pieChartModel, $data) {
                        $id = $data->id;
                        $nama = $data->nama;
                        $value = $data->total;
                        $warna[$data->id] = '#' . dechex(rand(0x000000, 0xFFFFFF));

                        return $pieChartModel->addSlice($nama, $value, $warna[$id]);
                    },
                    (new PieChartModel())
                        ->setTitle('Pekerjaan')
                        ->setAnimated($this->firstRun)
                        ->withDataLabels()
                        ->withOnSliceClickEvent('onSliceClick')
                );
        } elseif ($this->jenis == "agama") {
            $result = DB::table('tweb_penduduk')
                ->leftJoin('tweb_penduduk_agama', 'tweb_penduduk.agama_id', '=', 'tweb_penduduk_agama.id')
                ->select(
                    'tweb_penduduk_agama.id',
                    DB::raw('COALESCE(tweb_penduduk_agama.nama, "Belum Terdata") AS nama'),
                    DB::raw('COUNT(tweb_penduduk.id) AS total')
                )
                ->groupBy('tweb_penduduk_agama.id', 'nama')
                ->orderBy('tweb_penduduk_agama.id', 'asc')->get();

            $this->data = $result;
            $columnChartModel = $result
                ->reduce(
                    function (ColumnChartModel $columnChartModel, $data) {
                        $id = $data->id;
                        $nama = $data->nama;
                        $value = $data->total;
                        $warna[$data->id] = '#' . dechex(rand(0x000000, 0xFFFFFF));
                        return $columnChartModel->addColumn($nama, $value, $warna[$id]);
                    },
                    (new ColumnChartModel())
                        ->setTitle('Agama')
                        ->setAnimated($this->firstRun)
                        ->withOnColumnClickEventName('onColumnClick')
                );

            $pieChartModel = $result
                ->reduce(
                    function (PieChartModel $pieChartModel, $data) {
                        $id = $data->id;
                        $nama = $data->nama;
                        $value = $data->total;
                        $warna[$data->id] = '#' . dechex(rand(0x000000, 0xFFFFFF));

                        return $pieChartModel->addSlice($nama, $value, $warna[$id]);
                    },
                    (new PieChartModel())
                        ->setTitle('Agama')
                        ->setAnimated($this->firstRun)
                        ->withDataLabels()
                        ->withOnSliceClickEvent('onSliceClick')
                );
        } elseif ($this->jenis == "jenisKelamin") {
            $result = DB::table('tweb_penduduk')
                ->leftJoin('tweb_penduduk_sex', 'tweb_penduduk.sex', '=', 'tweb_penduduk_sex.id')
                ->select(
                    'tweb_penduduk_sex.id',
                    DB::raw('COALESCE(tweb_penduduk_sex.nama, "Belum Terdata") AS nama'),
                    DB::raw('COUNT(tweb_penduduk.id) AS total')
                )
                ->groupBy('tweb_penduduk_sex.id', 'nama')
                ->orderBy('tweb_penduduk_sex.id', 'asc')->get();

            $this->data = $result;
            $columnChartModel = $result
                ->reduce(
                    function (ColumnChartModel $columnChartModel, $data) {
                        $id = $data->id;
                        $nama = $data->nama;
                        $value = $data->total;
                        $warna[$data->id] = '#' . dechex(rand(0x000000, 0xFFFFFF));
                        return $columnChartModel->addColumn($nama, $value, $warna[$id]);
                    },
                    (new ColumnChartModel())
                        ->setTitle('Jenis Kelamin')
                        ->setAnimated($this->firstRun)
                        ->withOnColumnClickEventName('onColumnClick')
                );

            $pieChartModel = $result
                ->reduce(
                    function (PieChartModel $pieChartModel, $data) {
                        $id = $data->id;
                        $nama = $data->nama;
                        $value = $data->total;
                        $warna[$data->id] = '#' . dechex(rand(0x000000, 0xFFFFFF));

                        return $pieChartModel->addSlice($nama, $value, $warna[$id]);
                    },
                    (new PieChartModel())
                        ->setTitle('Jenis Kelamin')
                        ->setAnimated($this->firstRun)
                        ->withDataLabels()
                        ->withOnSliceClickEvent('onSliceClick')
                );
        } elseif ($this->jenis == "hubunganKk") {
            $result = DB::table('tweb_penduduk')
                ->leftJoin('tweb_penduduk_hubungan', 'tweb_penduduk.kk_level', '=', 'tweb_penduduk_hubungan.id')
                ->select(
                    'tweb_penduduk_hubungan.id',
                    DB::raw('COALESCE(tweb_penduduk_hubungan.nama, "Belum Terdata") AS nama'),
                    DB::raw('COUNT(tweb_penduduk.id) AS total')
                )
                ->groupBy('tweb_penduduk_hubungan.id', 'nama')
                ->orderBy('tweb_penduduk_hubungan.id', 'asc')->get();

            $this->data = $result;
            $columnChartModel = $result
                ->reduce(
                    function (ColumnChartModel $columnChartModel, $data) {
                        $id = $data->id;
                        $nama = $data->nama;
                        $value = $data->total;
                        $warna[$data->id] = '#' . dechex(rand(0x000000, 0xFFFFFF));
                        return $columnChartModel->addColumn($nama, $value, $warna[$id]);
                    },
                    (new ColumnChartModel())
                        ->setTitle('Hubungan Dalam KK')
                        ->setAnimated($this->firstRun)
                        ->withOnColumnClickEventName('onColumnClick')
                );

            $pieChartModel = $result
                ->reduce(
                    function (PieChartModel $pieChartModel, $data) {
                        $id = $data->id;
                        $nama = $data->nama;
                        $value = $data->total;
                        $warna[$data->id] = '#' . dechex(rand(0x000000, 0xFFFFFF));

                        return $pieChartModel->addSlice($nama, $value, $warna[$id]);
                    },
                    (new PieChartModel())
                        ->setTitle('Hubungan Dalam KK')
                        ->setAnimated($this->firstRun)
                        ->withDataLabels()
                        ->withOnSliceClickEvent('onSliceClick')
                );
        } elseif ($this->jenis == "wargaNegara") {
            $result = DB::table('tweb_penduduk')
                ->leftJoin('tweb_penduduk_warganegara', 'tweb_penduduk.warganegara_id', '=', 'tweb_penduduk_warganegara.id')
                ->select(
                    'tweb_penduduk_warganegara.id',
                    DB::raw('COALESCE(tweb_penduduk_warganegara.nama, "Belum Terdata") AS nama'),
                    DB::raw('COUNT(tweb_penduduk.id) AS total')
                )
                ->groupBy('tweb_penduduk_warganegara.id', 'nama')
                ->orderBy('tweb_penduduk_warganegara.id', 'asc')->get();

            $this->data = $result;
            $columnChartModel = $result
                ->reduce(
                    function (ColumnChartModel $columnChartModel, $data) {
                        $id = $data->id;
                        $nama = $data->nama;
                        $value = $data->total;
                        $warna[$data->id] = '#' . dechex(rand(0x000000, 0xFFFFFF));
                        return $columnChartModel->addColumn($nama, $value, $warna[$id]);
                    },
                    (new ColumnChartModel())
                        ->setTitle('Warga Negara')
                        ->setAnimated($this->firstRun)
                        ->withOnColumnClickEventName('onColumnClick')
                );

            $pieChartModel = $result
                ->reduce(
                    function (PieChartModel $pieChartModel, $data) {
                        $id = $data->id;
                        $nama = $data->nama;
                        $value = $data->total;
                        $warna[$data->id] = '#' . dechex(rand(0x000000, 0xFFFFFF));

                        return $pieChartModel->addSlice($nama, $value, $warna[$id]);
                    },
                    (new PieChartModel())
                        ->setTitle('Warga Negara')
                        ->setAnimated($this->firstRun)
                        ->withDataLabels()
                        ->withOnSliceClickEvent('onSliceClick')
                );
        } elseif ($this->jenis == "statusPenduduk") {
            $result = DB::table('tweb_penduduk')
                ->leftJoin('tweb_penduduk_status', 'tweb_penduduk.status', '=', 'tweb_penduduk_status.id')
                ->select(
                    'tweb_penduduk_status.id',
                    DB::raw('COALESCE(tweb_penduduk_status.nama, "Belum Terdata") AS nama'),
                    DB::raw('COUNT(tweb_penduduk.id) AS total')
                )
                ->groupBy('tweb_penduduk_status.id', 'nama')
                ->orderBy('tweb_penduduk_status.id', 'asc')->get();

            $this->data = $result;
            $columnChartModel = $result
                ->reduce(
                    function (ColumnChartModel $columnChartModel, $data) {
                        $id = $data->id;
                        $nama = $data->nama;
                        $value = $data->total;
                        $warna[$data->id] = '#' . dechex(rand(0x000000, 0xFFFFFF));
                        return $columnChartModel->addColumn($nama, $value, $warna[$id]);
                    },
                    (new ColumnChartModel())
                        ->setTitle('Warga Negara')
                        ->setAnimated($this->firstRun)
                        ->withOnColumnClickEventName('onColumnClick')
                );

            $pieChartModel = $result
                ->reduce(
                    function (PieChartModel $pieChartModel, $data) {
                        $id = $data->id;
                        $nama = $data->nama;
                        $value = $data->total;
                        $warna[$data->id] = '#' . dechex(rand(0x000000, 0xFFFFFF));

                        return $pieChartModel->addSlice($nama, $value, $warna[$id]);
                    },
                    (new PieChartModel())
                        ->setTitle('Warga Negara')
                        ->setAnimated($this->firstRun)
                        ->withDataLabels()
                        ->withOnSliceClickEvent('onSliceClick')
                );
        } elseif ($this->jenis == "golonganDarah") {
            $result = DB::table('tweb_penduduk')
                ->leftJoin('tweb_golongan_darah', 'tweb_penduduk.golongan_darah_id', '=', 'tweb_golongan_darah.id')
                ->select(
                    'tweb_golongan_darah.id',
                    DB::raw('COALESCE(tweb_golongan_darah.nama, "Belum Terdata") AS nama'),
                    DB::raw('COUNT(tweb_penduduk.id) AS total')
                )
                ->groupBy('tweb_golongan_darah.id', 'nama')
                ->orderBy('tweb_golongan_darah.id', 'asc')->get();

            $this->data = $result;
            $columnChartModel = $result
                ->reduce(
                    function (ColumnChartModel $columnChartModel, $data) {
                        $id = $data->id;
                        $nama = $data->nama;
                        $value = $data->total;
                        $warna[$data->id] = '#' . dechex(rand(0x000000, 0xFFFFFF));
                        return $columnChartModel->addColumn($nama, $value, $warna[$id]);
                    },
                    (new ColumnChartModel())
                        ->setTitle('Golongan Darah')
                        ->setAnimated($this->firstRun)
                        ->withOnColumnClickEventName('onColumnClick')
                );

            $pieChartModel = $result
                ->reduce(
                    function (PieChartModel $pieChartModel, $data) {
                        $id = $data->id;
                        $nama = $data->nama;
                        $value = $data->total;
                        $warna[$data->id] = '#' . dechex(rand(0x000000, 0xFFFFFF));

                        return $pieChartModel->addSlice($nama, $value, $warna[$id]);
                    },
                    (new PieChartModel())
                        ->setTitle('Golongan Darah')
                        ->setAnimated($this->firstRun)
                        ->withDataLabels()
                        ->withOnSliceClickEvent('onSliceClick')
                );
        } elseif ($this->jenis == "penyandangCacat") {
            $result = DB::table('tweb_penduduk')
                ->leftJoin('tweb_cacat', 'tweb_penduduk.cacat_id', '=', 'tweb_cacat.id')
                ->select(
                    'tweb_cacat.id',
                    DB::raw('COALESCE(tweb_cacat.nama, "Belum Terdata") AS nama'),
                    DB::raw('COUNT(tweb_penduduk.id) AS total')
                )
                ->groupBy('tweb_cacat.id', 'nama')
                ->orderBy('tweb_cacat.id', 'asc')->get();

            $this->data = $result;
            $columnChartModel = $result
                ->reduce(
                    function (ColumnChartModel $columnChartModel, $data) {
                        $id = $data->id;
                        $nama = $data->nama;
                        $value = $data->total;
                        $warna[$data->id] = '#' . dechex(rand(0x000000, 0xFFFFFF));
                        return $columnChartModel->addColumn($nama, $value, $warna[$id]);
                    },
                    (new ColumnChartModel())
                        ->setTitle('Penyandang Cacat')
                        ->setAnimated($this->firstRun)
                        ->withOnColumnClickEventName('onColumnClick')
                );

            $pieChartModel = $result
                ->reduce(
                    function (PieChartModel $pieChartModel, $data) {
                        $id = $data->id;
                        $nama = $data->nama;
                        $value = $data->total;
                        $warna[$data->id] = '#' . dechex(rand(0x000000, 0xFFFFFF));

                        return $pieChartModel->addSlice($nama, $value, $warna[$id]);
                    },
                    (new PieChartModel())
                        ->setTitle('Penyandang Cacat')
                        ->setAnimated($this->firstRun)
                        ->withDataLabels()
                        ->withOnSliceClickEvent('onSliceClick')
                );
        } elseif ($this->jenis == "penyakitMenahun") {
            $result = DB::table('tweb_penduduk')
                ->leftJoin('tweb_sakit_menahun', 'tweb_penduduk.sakit_menahun_id', '=', 'tweb_sakit_menahun.id')
                ->select(
                    'tweb_sakit_menahun.id',
                    DB::raw('COALESCE(tweb_sakit_menahun.nama, "Belum Terdata") AS nama'),
                    DB::raw('COUNT(tweb_penduduk.id) AS total')
                )
                ->groupBy('tweb_sakit_menahun.id', 'nama')
                ->orderBy('tweb_sakit_menahun.id', 'asc')->get();

            $this->data = $result;
            $columnChartModel = $result
                ->reduce(
                    function (ColumnChartModel $columnChartModel, $data) {
                        $id = $data->id;
                        $nama = $data->nama;
                        $value = $data->total;
                        $warna[$data->id] = '#' . dechex(rand(0x000000, 0xFFFFFF));
                        return $columnChartModel->addColumn($nama, $value, $warna[$id]);
                    },
                    (new ColumnChartModel())
                        ->setTitle('Penyakit Menahun')
                        ->setAnimated($this->firstRun)
                        ->withOnColumnClickEventName('onColumnClick')
                );

            $pieChartModel = $result
                ->reduce(
                    function (PieChartModel $pieChartModel, $data) {
                        $id = $data->id;
                        $nama = $data->nama;
                        $value = $data->total;
                        $warna[$data->id] = '#' . dechex(rand(0x000000, 0xFFFFFF));

                        return $pieChartModel->addSlice($nama, $value, $warna[$id]);
                    },
                    (new PieChartModel())
                        ->setTitle('Penyakit Menahun')
                        ->setAnimated($this->firstRun)
                        ->withDataLabels()
                        ->withOnSliceClickEvent('onSliceClick')
                );
        } elseif ($this->jenis == "akseptorKB") {
            $result = DB::table('tweb_penduduk')
                ->leftJoin('tweb_cara_kb', 'tweb_penduduk.cara_kb_id', '=', 'tweb_cara_kb.id')
                ->select(
                    'tweb_cara_kb.id',
                    DB::raw('COALESCE(tweb_cara_kb.nama, "Belum Terdata") AS nama'),
                    DB::raw('COUNT(tweb_penduduk.id) AS total')
                )
                ->groupBy('tweb_cara_kb.id', 'nama')
                ->orderBy('tweb_cara_kb.id', 'asc')->get();

            $this->data = $result;
            $columnChartModel = $result
                ->reduce(
                    function (ColumnChartModel $columnChartModel, $data) {
                        $id = $data->id;
                        $nama = $data->nama;
                        $value = $data->total;
                        $warna[$data->id] = '#' . dechex(rand(0x000000, 0xFFFFFF));
                        return $columnChartModel->addColumn($nama, $value, $warna[$id]);
                    },
                    (new ColumnChartModel())
                        ->setTitle('Akseptor KB')
                        ->setAnimated($this->firstRun)
                        ->withOnColumnClickEventName('onColumnClick')
                );

            $pieChartModel = $result
                ->reduce(
                    function (PieChartModel $pieChartModel, $data) {
                        $id = $data->id;
                        $nama = $data->nama;
                        $value = $data->total;
                        $warna[$data->id] = '#' . dechex(rand(0x000000, 0xFFFFFF));

                        return $pieChartModel->addSlice($nama, $value, $warna[$id]);
                    },
                    (new PieChartModel())
                        ->setTitle('Akseptor KB')
                        ->setAnimated($this->firstRun)
                        ->withDataLabels()
                        ->withOnSliceClickEvent('onSliceClick')
                );
        } elseif ($this->jenis == "kepemilikanKtp") {
            $result = DB::table('tweb_penduduk')
                ->leftJoin('tweb_status_ktp', 'tweb_penduduk.status_rekam', '=', 'tweb_status_ktp.status_rekam')
                ->select(
                    'tweb_status_ktp.status_rekam',
                    DB::raw('COALESCE(tweb_status_ktp.nama, "Belum Terdata") AS nama'),
                    DB::raw('COUNT(tweb_penduduk.id) AS total')
                )
                ->groupBy('tweb_status_ktp.status_rekam', 'nama')
                ->orderBy('tweb_status_ktp.status_rekam', 'asc')->get();

            $this->data = $result;
            $columnChartModel = $result
                ->reduce(
                    function (ColumnChartModel $columnChartModel, $data) {
                        $id = $data->status_rekam;
                        $nama = $data->nama;
                        $value = $data->total;
                        $warna[$data->status_rekam] = '#' . dechex(rand(0x000000, 0xFFFFFF));
                        return $columnChartModel->addColumn($nama, $value, $warna[$id]);
                    },
                    (new ColumnChartModel())
                        ->setTitle('Kepemilikan KTP')
                        ->setAnimated($this->firstRun)
                        ->withOnColumnClickEventName('onColumnClick')
                );

            $pieChartModel = $result
                ->reduce(
                    function (PieChartModel $pieChartModel, $data) {
                        $id = $data->status_rekam;
                        $nama = $data->nama;
                        $value = $data->total;
                        $warna[$data->status_rekam] = '#' . dechex(rand(0x000000, 0xFFFFFF));

                        return $pieChartModel->addSlice($nama, $value, $warna[$id]);
                    },
                    (new PieChartModel())
                        ->setTitle('Kepemilkan KTP')
                        ->setAnimated($this->firstRun)
                        ->withDataLabels()
                        ->withOnSliceClickEvent('onSliceClick')
                );
        } elseif ($this->jenis == "asuransiKesehatan") {
            $result = DB::table('tweb_penduduk')
                ->leftJoin('tweb_penduduk_asuransi', 'tweb_penduduk.id_asuransi', '=', 'tweb_penduduk_asuransi.id')
                ->select(
                    'tweb_penduduk_asuransi.id',
                    DB::raw('COALESCE(tweb_penduduk_asuransi.nama, "Belum Terdata") AS nama'),
                    DB::raw('COUNT(tweb_penduduk.id) AS total')
                )
                ->groupBy('tweb_penduduk_asuransi.id', 'nama')
                ->orderBy('tweb_penduduk_asuransi.id', 'asc')->get();

            $this->data = $result;
            $columnChartModel = $result
                ->reduce(
                    function (ColumnChartModel $columnChartModel, $data) {
                        $id = $data->id;
                        $nama = $data->nama;
                        $value = $data->total;
                        $warna[$data->id] = '#' . dechex(rand(0x000000, 0xFFFFFF));
                        return $columnChartModel->addColumn($nama, $value, $warna[$id]);
                    },
                    (new ColumnChartModel())
                        ->setTitle('Asuransi Kesehatan')
                        ->setAnimated($this->firstRun)
                        ->withOnColumnClickEventName('onColumnClick')
                );

            $pieChartModel = $result
                ->reduce(
                    function (PieChartModel $pieChartModel, $data) {
                        $id = $data->id;
                        $nama = $data->nama;
                        $value = $data->total;
                        $warna[$data->id] = '#' . dechex(rand(0x000000, 0xFFFFFF));

                        return $pieChartModel->addSlice($nama, $value, $warna[$id]);
                    },
                    (new PieChartModel())
                        ->setTitle('Asuransi Kesehatan')
                        ->setAnimated($this->firstRun)
                        ->withDataLabels()
                        ->withOnSliceClickEvent('onSliceClick')
                );
        } elseif ($this->jenis == "sukuEtnis") {
            $result = DB::table('tweb_penduduk')
                ->leftJoin('tweb_penduduk_asuransi', 'tweb_penduduk.id_asuransi', '=', 'tweb_penduduk_asuransi.id')
                ->select(
                    'tweb_penduduk_asuransi.id',
                    DB::raw('COALESCE(tweb_penduduk_asuransi.nama, "Belum Terdata") AS nama'),
                    DB::raw('COUNT(tweb_penduduk.id) AS total')
                )
                ->groupBy('tweb_penduduk_asuransi.id', 'nama')
                ->orderBy('tweb_penduduk_asuransi.id', 'asc')->get();

            $this->data = $result;
            $columnChartModel = $result
                ->reduce(
                    function (ColumnChartModel $columnChartModel, $data) {
                        $id = $data->id;
                        $nama = $data->nama;
                        $value = $data->total;
                        $warna[$data->id] = '#' . dechex(rand(0x000000, 0xFFFFFF));
                        return $columnChartModel->addColumn($nama, $value, $warna[$id]);
                    },
                    (new ColumnChartModel())
                        ->setTitle('Asuransi Kesehatan')
                        ->setAnimated($this->firstRun)
                        ->withOnColumnClickEventName('onColumnClick')
                );

            $pieChartModel = $result
                ->reduce(
                    function (PieChartModel $pieChartModel, $data) {
                        $id = $data->id;
                        $nama = $data->nama;
                        $value = $data->total;
                        $warna[$data->id] = '#' . dechex(rand(0x000000, 0xFFFFFF));

                        return $pieChartModel->addSlice($nama, $value, $warna[$id]);
                    },
                    (new PieChartModel())
                        ->setTitle('Asuransi Kesehatan')
                        ->setAnimated($this->firstRun)
                        ->withDataLabels()
                        ->withOnSliceClickEvent('onSliceClick')
                );
        }
        return view('livewire.front.statistik')->with([
            'columnChartModel' => $columnChartModel ?? '',
            'pieChartModel' => $pieChartModel ?? ''
        ]);
    }
}
