<div>
    <section class="wrapper bg-light">
        <div class="container pb-2 pb-md-1 pt-5">
            <div class="row gy-4">
                <div class="row gx-md-1 gy-1 text-center">
                    <div class="messages"></div>
                    <div class="row gx-4">
                        <div class="col-md-5">
                            <div class="form-select-wrapper">
                                <select class="form-select{{ $errors->has('selectKecamatan') ? ' border-danger' : '' }}"
                                    wire:model="selectKecamatan" wire:change="updateDesa">
                                    <option> Pilih Kecamatan </option>
                                    @foreach (get_kecamatan() ?? [] as $kecamatan)
                                        <option value={{ $kecamatan->kode_kecamatan }}>
                                            {{ $kecamatan->nama_kecamatan }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- /column -->
                        <div class="col-md-5">
                            <div class="form-select-wrapper" wire:model="selectDesa">
                                <select class="form-select {{ $errors->has('selectDesa') ? ' border-danger' : '' }}">
                                    <option> Pilih Desa </option>
                                    @foreach ($listDesa ?? [] as $desa)
                                        <option value={{ $desa->id }}>
                                            {{ $desa->nama_desa }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- /column -->
                        <div class="col-md-2">
                            <div class="form-floating mb-4">
                                <button type="submit" class="btn btn-primary rounded-pill btn-send mb-3"
                                    wire:click="tampilkan">Tampilkan
                                </button>
                            </div>
                        </div>
                        <!-- /column -->
                    </div>
                </div>

                @if ($show)
                    <div class="col-lg-9 order-lg-1">
                        <div class="container pb-15 pb-md-1 mt-10">
                            <div class="row gx-md-5 gy-5 text-center">
                                <div class="col-md-6 col-xl-3">
                                    <div class="card shadow-lg">
                                        <div class="card-body">
                                            <h4>Penduduk</h4>
                                            <p class="mb-2">{{ $totalPendudukDesa ?? '' }}</p>
                                        </div>
                                        <!--/.card-body -->
                                    </div>
                                    <!--/.card -->
                                </div>
                                <!--/column -->
                                <div class="col-md-6 col-xl-3">
                                    <div class="card shadow-lg">
                                        <div class="card-body">
                                            <h4>Keluarga</h4>
                                            <p class="mb-2">{{ $totalKeluargaDesa ?? '' }}</p>
                                        </div>
                                        <!--/.card-body -->
                                    </div>
                                    <!--/.card -->
                                </div>
                                <!--/column -->
                                <div class="col-md-6 col-xl-3">
                                    <div class="card shadow-lg">
                                        <div class="card-body">
                                            <h4>RTM</h4>
                                            <p class="mb-2">{{ $rtmDesa ?? 0 }}</p>
                                        </div>
                                        <!--/.card-body -->
                                    </div>
                                    <!--/.card -->
                                </div>
                                <!--/column -->
                                <div class="col-md-6 col-xl-3">
                                    <div class="card shadow-lg">
                                        <div class="card-body">
                                            <h4>Bantuan</h4>
                                            <p class="mb-2">{{ $bantuanDesa ?? 0 }}</p>
                                        </div>
                                        <!--/.card-body -->
                                    </div>
                                    <!--/.card -->
                                </div>
                                <!--/column -->
                            </div>
                            <br>

                        </div>
                        <div class="row align-items-center mb-2 position-relative zindex-1">
                            <div class="col-md-7 col-xl-12 pe-xl-20">
                                <h2 class="display-12 mb-1">Data Statistik {{ $judul ?? '' }} - Desa
                                    {{ $cari->nama_desa ?? '' }}</h2>
                            </div>
                            <!--/column -->

                            <!--/column -->
                        </div>
                        <div class="grid grid-view projects-masonry shop mb-5">
                            <div class="row gx-md-8 gy-10 gy-md-13 isotope">
                                <div class="col" style="height: 20rem;">
                                    <div id="chart-container"></div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="grid grid-view projects-masonry shop mb-5">
                            <div class="row gx-md-8 gy-10 gy-md-13 isotope">
                                <div class="col" style="height: 15rem;">
                                    <div id="pie-container"></div>
                                </div>
                            </div>
                        </div>
                        <br>
                        @if ($jenis == 'pendidikan_kk')
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($data as $row)
                                        <tr>
                                            <th scope="row">{{ $no++ }}</th>
                                            <td>{{ $row->nama }}</td>
                                            <td>{{ $row->total }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @elseif($jenis == 'pendidikan')
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($data as $row)
                                        <tr>
                                            <th scope="row">{{ $no++ }}</th>
                                            <td>{{ $row->nama }}</td>
                                            <td>{{ $row->total }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @elseif($jenis == 'rentangUmur')
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($data as $row)
                                        <tr>
                                            <th scope="row">{{ $no++ }}</th>
                                            <td>{{ $row->nama }}</td>
                                            <td>{{ $row->total }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @elseif($jenis == 'kategoriUmur')
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($data as $row)
                                        <tr>
                                            <th scope="row">{{ $no++ }}</th>
                                            <td>{{ $row->nama }}</td>
                                            <td>{{ $row->total }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @elseif($jenis == 'pekerjaan')
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($data as $row)
                                        <tr>
                                            <th scope="row">{{ $no++ }}</th>
                                            <td>{{ $row->nama }}</td>
                                            <td>{{ $row->total }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @elseif($jenis == 'statusPerkawinan')
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($data as $row)
                                        <tr>
                                            <th scope="row">{{ $no++ }}</th>
                                            <td>{{ $row->nama }}</td>
                                            <td>{{ $row->total }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @elseif($jenis == 'agama')
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($data as $row)
                                        <tr>
                                            <th scope="row">{{ $no++ }}</th>
                                            <td>{{ $row->nama }}</td>
                                            <td>{{ $row->total }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @elseif($jenis == 'jenisKelamin')
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($data as $row)
                                        <tr>
                                            <th scope="row">{{ $no++ }}</th>
                                            <td>{{ $row->nama }}</td>
                                            <td>{{ $row->total }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @elseif($jenis == 'hubunganKk')
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($data as $row)
                                        <tr>
                                            <th scope="row">{{ $no++ }}</th>
                                            <td>{{ $row->nama }}</td>
                                            <td>{{ $row->total }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @elseif($jenis == 'wargaNegara')
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($data as $row)
                                        <tr>
                                            <th scope="row">{{ $no++ }}</th>
                                            <td>{{ $row->nama }}</td>
                                            <td>{{ $row->total }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @elseif($jenis == 'statusPenduduk')
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($data as $row)
                                        <tr>
                                            <th scope="row">{{ $no++ }}</th>
                                            <td>{{ $row->nama }}</td>
                                            <td>{{ $row->total }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @elseif($jenis == 'golonganDarah')
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($data as $row)
                                        <tr>
                                            <th scope="row">{{ $no++ }}</th>
                                            <td>{{ $row->nama }}</td>
                                            <td>{{ $row->total }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @elseif($jenis == 'penyandangCacat')
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($data as $row)
                                        <tr>
                                            <th scope="row">{{ $no++ }}</th>
                                            <td>{{ $row->nama }}</td>
                                            <td>{{ $row->total }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @elseif($jenis == 'penyakitMenahun')
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($data as $row)
                                        <tr>
                                            <th scope="row">{{ $no++ }}</th>
                                            <td>{{ $row->nama }}</td>
                                            <td>{{ $row->total }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @elseif($jenis == 'akseptorKB')
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($data as $row)
                                        <tr>
                                            <th scope="row">{{ $no++ }}</th>
                                            <td>{{ $row->nama }}</td>
                                            <td>{{ $row->total }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @elseif($jenis == 'kepemilikanKtp')
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($data as $row)
                                        <tr>
                                            <th scope="row">{{ $no++ }}</th>
                                            <td>{{ $row->nama }}</td>
                                            <td>{{ $row->total }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @elseif($jenis == 'asuransiKesehatan')
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($data as $row)
                                        <tr>
                                            <th scope="row">{{ $no++ }}</th>
                                            <td>{{ $row->nama }}</td>
                                            <td>{{ $row->total }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @elseif($jenis == 'sukuEtnis')
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($data as $row)
                                        <tr>
                                            <th scope="row">{{ $no++ }}</th>
                                            <td>{{ $row->nama }}</td>
                                            <td>{{ $row->total }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @elseif($jenis == 'kelasSosial')
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th rowspan="2" scope="col">#</th>
                                        <th rowspan="2" scope="col">Name</th>
                                        <th colspan="2" scope="col" style="text-align:center">Jumlah</th>
                                        <th colspan="2" scope="col" style="text-align:center">Laki - Laki</th>
                                        <th colspan="2" scope="col" style="text-align:center">Perempuan</th>

                                    </tr>
                                    <tr>
                                        <th scope="col">n</th>
                                        <th scope="col">%</th>
                                        <th scope="col">n</th>
                                        <th scope="col">%</th>
                                        <th scope="col">n</th>
                                        <th scope="col">%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                        $totalpresentasi = 0;
                                        $totallakipresen = 0;
                                        $totalcewepresen = 0;
                                    @endphp
                                    @foreach ($data as $row)
                                        <tr>
                                            <th scope="row">{{ $no++ }}</th>
                                            <td>{{ $row->nama }}</td>
                                            <td>{{ $row->total }}</td>
                                            <td>{{ number_format(($row->total / $baris_total['jumlah']) * 100, 2) }}%</td>
                                            <td>{{ $row->laki }} </td>
                                            <td>{{ number_format(($row->laki / $baris_total['jumlah']) * 100, 2) }}%</td>
                                            <td>{{ $row->perempuan }}</td>
                                            <td>{{ number_format(($row->perempuan / $baris_total['jumlah']) * 100, 2) }}%
                                            </td>
                                        </tr>
                                        @php
                                            $totalpresentasi += number_format(
                                                ($row->total / $baris_total['jumlah']) * 100,
                                                2,
                                            );
                                            $totallakipresen += number_format(
                                                ($row->laki / $baris_total['jumlah']) * 100,
                                                2,
                                            );
                                            $totalcewepresen += number_format(
                                                ($row->perempuan / $baris_total['jumlah']) * 100,
                                                2,
                                            );
                                        @endphp
                                    @endforeach
                                    <tr>
                                        <td colspan="2" class="text-center font-weight-bold">jumlah</td>
                                        <td class="text-right"> {{ $jumlah }}</td>
                                        <td class="text-right">{{ $totalpresentasi }} %</td>
                                        <td class="text-right"> {{ $totallaki }}</td>
                                        <td class="text-right">{{ $totallakipresen }} %</td>
                                        <td class="text-right"> {{ $totalperem }}</td>
                                        <td class="text-right">{{ $totalcewepresen }} %</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-center font-weight-bold">Belum Mengisi</td>
                                        <td class="text-right"> {{ $baris_belum['jumlah'] }}</td>
                                        <td class="text-right">%</td>
                                        <td class="text-right"> {{ $baris_belum['laki'] }}</td>
                                        <td class="text-right">%</td>
                                        <td class="text-right"> {{ $baris_belum['cewe'] }}</td>
                                        <td class="text-right">%</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-center font-weight-bold">Total</td>
                                        <td class="text-right"> {{ $baris_total['jumlah'] }}</td>
                                        <td class="text-right">%</td>
                                        <td class="text-right"> {{ $baris_total['laki'] }}</td>
                                        <td class="text-right">%</td>
                                        <td class="text-right"> {{ $baris_total['cewe'] }}</td>
                                        <td class="text-right">%</td>
                                    </tr>
                                </tbody>
                            </table>
                        @else
                            <p>tidak ada data</p>
                        @endif
                    </div>
                    <!-- /column -->
                    <aside class="col-lg-3 sidebar">
                        <div class="widget mt-1">
                            <ul class="list-unstyled ps-0">
                                <li class="mb-1">
                                    <h4> <a href="#" class="align-items-center rounded link-body"
                                            data-bs-toggle="collapse" data-bs-target="#clothing-collapse"
                                            aria-expanded="true">Statistik Penduduk
                                        </a></h4>
                                    <div class="collapse show mt-1" id="clothing-collapse" style="">
                                        <ul class="btn-toggle-nav list-unstyled ps-2">
                                            <li><span style="cursor:pointer" wire:click="rentangUmur"
                                                    class="link-body">Rentang Umur</span></li>
                                            <li><span style="cursor:pointer" wire:click="kategoriUmur"
                                                    class="link-body">Kategori Umur</span></li>
                                            <li><span style="cursor:pointer" wire:click="pendidikanKk"
                                                    class="link-body">Pendidikan
                                                    Dalam KK</span></li>
                                            {{-- done --}}
                                            <li><span style="cursor:pointer" wire:click="pendidikanDitempuh"
                                                    class="link-body">Pendidikan Sedang
                                                    Ditempuh</span></li>
                                            {{-- done --}}
                                            <li><span style="cursor:pointer" wire:click="pekerjaan"
                                                    class="link-body">Pekerjaan</span></li>
                                            {{-- done --}}
                                            <li><span style="cursor:pointer" wire:click="statusPerkawinan"
                                                    class="link-body">Status Perkawinan</span></li>
                                            {{-- done --}}
                                            <li><span style="cursor:pointer" wire:click="agama"
                                                    class="link-body">Agama</span></li>
                                            {{-- done --}}
                                            <li><span style="cursor:pointer" wire:click="jenisKelamin"
                                                    class="link-body">Jenis Kelamin</span></li>
                                            {{-- done --}}
                                            <li><span style="cursor:pointer" wire:click="hubunganKk"
                                                    class="link-body">Hubungan Dalam KK</span></li>
                                            <li><span style="cursor:pointer" wire:click="wargaNegara"
                                                    class="link-body">Warga Negara</span></li>
                                            <li><span style="cursor:pointer" wire:click="statusPenduduk"
                                                    class="link-body">Status Penduduk</span></li>
                                            <li><span style="cursor:pointer" wire:click="golonganDarah"
                                                    class="link-body">Golongan Darah</span></li>
                                            <li><span style="cursor:pointer" wire:click="penyandangCacat"
                                                    class="link-body">Penyandang Cacat</span></li>
                                            <li><span style="cursor:pointer" wire:click="penyakitMenahun"
                                                    class="link-body">Penyakit Menahun</span></li>
                                            <li><span style="cursor:pointer" wire:click="akseptorKB"
                                                    class="link-body">Akseptor KB</span></li>
                                            <li><span style="cursor:pointer" wire:click="aktaKelahiran"
                                                    class="link-body">Akta Kelahiran</span></li>
                                            <li><span style="cursor:pointer" wire:click="kepemilikanKtp"
                                                    class="link-body">Kepemilikan KTP</span></li>
                                            <li><span style="cursor:pointer" wire:click="asuransiKesehatan"
                                                    class="link-body">Asuransi Kesehatan</span></li>
                                            <li><span style="cursor:pointer"
                                                    wire:click="statusCovid"class="link-body">Status Covid</span></li>
                                            <li><span style="cursor:pointer"
                                                    wire:click="sukuEtnis"class="link-body">Suku / Etnis</span></li>
                                            <li><span style="cursor:pointer"
                                                    wire:click="bpjsKetenagakerjaan"class="link-body">BPJS
                                                    Ketenagakerjaan</span></li>
                                            <li><a span style="cursor:pointer"
                                                    wire:click="statusKehamilan"class="link-body">Status Kehamilan</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="mb-1">
                                    <h4> <a href="#" class="align-items-center rounded link-body"
                                            data-bs-toggle="collapse" data-bs-target="#keluarga-collapse"
                                            aria-expanded="false">Statistik Keluarga
                                        </a></h4>
                                    <div class="collapse mt-1" id="keluarga-collapse" style="">
                                        <ul class="btn-toggle-nav list-unstyled ps-2">
                                            <li><span style="cursor:pointer" wire:click="kelasSosial"
                                                    class="link-body">Kelas Sosial</span></li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="mb-1">
                                    <h4> <a href="#" class="align-items-center rounded collapsed link-body"
                                            data-bs-toggle="collapse" data-bs-target="#bantuan-collapse"
                                            aria-expanded="false">Statistik Bantuan
                                        </a></h4>
                                    <div class="collapse mt-1" id="bantuan-collapse" style="">
                                        <ul class="btn-toggle-nav list-unstyled ps-2">
                                            <li><span style="cursor:pointer"
                                                    wire:click="bantuanPenduduk"class="link-body">Penerima Bantuan
                                                    Penduduk</span></li>
                                            <li><span style="cursor:pointer" wire:click="bantuanKeluarga"
                                                    class="link-body">Penerima Bantuan Keluarga</span></li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="mb-1">
                                    <h4> <a href="#" class="align-items-center rounded collapsed link-body"
                                            data-bs-toggle="collapse" data-bs-target="#rtm-collapse"
                                            aria-expanded="false"> Statistik RTM
                                        </a> </h4>
                                    <div class="collapse mt-1" id="rtm-collapse" style="">
                                        <ul class="btn-toggle-nav list-unstyled ps-2">
                                            <li><span style="cursor:pointer" wire:click="rtm"
                                                    class="link-body">BDT</span></li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <!-- /.widget -->
                    </aside>
                    <!-- /column .sidebar -->
                @else
                    <div class="col-lg-12 mt-lg-3 text-center text-lg-middle" data-cues="slideInDown"
                        data-group="page-title" data-delay="600">
                        <h4 class="display-1 mb-5 center">Data Statistik</h4>
                        <p class="lead fs-25 lh-sm mb-7 px-md-10 px-lg-0">Desa / Kelurahan belum dipilih.</p>

                    </div>
                @endif
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container -->
    </section>
</div>

@push('js')
    <script src="{{ asset('costum/js/columnchart.js') }}"></script>
    <script src="{{ asset('fusioncharts/fusioncharts.js') }}"></script>
    <script src="{{ asset('fusioncharts/themes/fusioncharts.theme.ocean.js') }}"></script>
    <script>
        document.addEventListener('livewire:init', function() {
            window.addEventListener('column', event => {
                var dataArray = event.detail.data;
                columnchart(dataArray);
            });
        });
    </script>
@endpush
