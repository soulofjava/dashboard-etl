<?php

namespace App\Livewire\Front;

use App\Livewire\Front\Chart\ColumnChart;
use App\Models\Config;
use App\Models\TwebPendudukPendidikanKk;
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

    public function pekerjaan()
    {
        $this->jenis = "pekerjaan";
    }
    public function pendidikanKk()
    {
        $this->jenis = "pendidikan_kk";
    }

    public function pendidikanDitempuh()
    {
        $this->jenis = "pendidikan";
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
            $this->jenis = "pendidikan_kk";
            $this->configId = $cari->id;
        }
    }
    public function render()
    {
        if ($this->jenis == "pendidikan_kk") {
            $result = DB::table('tweb_penduduk')
                ->join('tweb_penduduk_pendidikan_kk', 'tweb_penduduk.pendidikan_kk_id', '=', 'tweb_penduduk_pendidikan_kk.id')
                ->select('tweb_penduduk_pendidikan_kk.id', 'tweb_penduduk_pendidikan_kk.nama', DB::raw('COUNT(tweb_penduduk_pendidikan_kk.nama) as total'))
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
                        ->withOnSliceClickEvent('onSliceClick')
                );
        } elseif ($this->jenis == "pendidikan") {
            $result = DB::table('tweb_penduduk')
                ->join('tweb_penduduk_pendidikan', 'tweb_penduduk.pendidikan_sedang_id', '=', 'tweb_penduduk_pendidikan.id')
                ->select('tweb_penduduk_pendidikan.id', 'tweb_penduduk_pendidikan.nama', DB::raw('COUNT(tweb_penduduk_pendidikan.nama) as total'))
                ->where('tweb_penduduk.config_id', '=', $this->configId)
                ->groupBy('tweb_penduduk_pendidikan.id')
                ->orderBy('tweb_penduduk_pendidikan.id', 'asc')->get();

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
                        ->withOnSliceClickEvent('onSliceClick')
                );
        } elseif ($this->jenis == "statusPerkawinan") {
            $result = DB::table('tweb_penduduk')
                ->join('tweb_penduduk_kawin', 'tweb_penduduk.status_kawin', '=', 'tweb_penduduk_kawin.id')
                ->select('tweb_penduduk_kawin.id', 'tweb_penduduk_kawin.nama', DB::raw('COUNT(tweb_penduduk_kawin.nama) as total'))
                ->where('tweb_penduduk.config_id', '=', $this->configId)
                ->groupBy('tweb_penduduk_kawin.id')
                ->orderBy('tweb_penduduk_kawin.id', 'asc')->get();

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
                        ->withOnSliceClickEvent('onSliceClick')
                );
        } elseif ($this->jenis == "pekerjaan") {
            $result = DB::table('tweb_penduduk')
                ->join('tweb_penduduk_pekerjaan', 'tweb_penduduk.pekerjaan_id', '=', 'tweb_penduduk_pekerjaan.id')
                ->select('tweb_penduduk_pekerjaan.id', 'tweb_penduduk_pekerjaan.nama', DB::raw('COUNT(tweb_penduduk_pekerjaan.nama) as total'))
                ->where('tweb_penduduk.config_id', '=', $this->configId)
                ->groupBy('tweb_penduduk_pekerjaan.id')
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
                        ->withOnSliceClickEvent('onSliceClick')
                );
        } elseif ($this->jenis == "agama") {
            $result = DB::table('tweb_penduduk')
                ->join('tweb_penduduk_agama', 'tweb_penduduk.agama_id', '=', 'tweb_penduduk_agama.id')
                ->select('tweb_penduduk_agama.id', 'tweb_penduduk_agama.nama', DB::raw('COUNT(tweb_penduduk_agama.nama) as total'))
                ->where('tweb_penduduk.config_id', '=', $this->configId)
                ->groupBy('tweb_penduduk_agama.id')
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
                        ->withOnSliceClickEvent('onSliceClick')
                );
        } elseif ($this->jenis == "jenisKelamin") {
            $result = DB::table('tweb_penduduk')
                ->join('tweb_penduduk_sex', 'tweb_penduduk.sex', '=', 'tweb_penduduk_sex.id')
                ->select('tweb_penduduk_sex.id', 'tweb_penduduk_sex.nama', DB::raw('COUNT(tweb_penduduk_sex.nama) as total'))
                ->where('tweb_penduduk.config_id', '=', $this->configId)
                ->groupBy('tweb_penduduk_sex.id')
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
                        ->withOnSliceClickEvent('onSliceClick')
                );
        }

        return view('livewire.front.statistik')->with([
            'columnChartModel' => $columnChartModel ?? '',
            'pieChartModel' => $pieChartModel ?? ''
        ]);
    }
}
