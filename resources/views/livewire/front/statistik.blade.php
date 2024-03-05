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
                    <!-- /.row -->
                </div>

                @if ($show)
                    <div class="col-lg-9 order-lg-1">
                        <div class="row align-items-center mb-10 position-relative zindex-1">
                            <div class="col-md-7 col-xl-8 pe-xl-20">
                                <h2 class="display-6 mb-1">Data Statistik</h2>
                            </div>
                            <!--/column -->

                            <!--/column -->
                        </div>
                        <!--/.row -->
                        <div class="grid grid-view projects-masonry shop mb-13">
                            <div class="row gx-md-8 gy-10 gy-md-13 isotope">
                                <div class="col" style="height: 20rem;">
                                    <livewire:livewire-column-chart key="{{ $columnChartModel->reactiveKey() }}"
                                        :column-chart-model="$columnChartModel" />
                                </div>
                                <!-- /.item -->
                            </div>

                            <!-- /.row -->
                        </div>
                        <!-- /.grid -->
                        <div class="grid grid-view projects-masonry shop mb-13">
                            <div class="row gx-md-8 gy-10 gy-md-13 isotope">
                                <div class="col" style="height: 15rem;">
                                    <livewire:livewire-pie-chart key="{{ $pieChartModel->reactiveKey() }}"
                                        :pie-chart-model="$pieChartModel" />
                                </div>
                                <!-- /.item -->
                            </div>
                        </div>
                        @if ($jenis == 'pendidikan_kk')
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
                        @else
                            <p>tidak ada data</p>
                        @endif
                        <!-- /nav -->
                    </div>
                    <!-- /column -->
                    <aside class="col-lg-3 sidebar">
                        <div class="widget mt-1">
                            <ul class="list-unstyled ps-0">
                                <li class="mb-1">
                                    <h4> <a href="#" class="align-items-center rounded link-body"
                                            data-bs-toggle="collapse" data-bs-target="#clothing-collapse"
                                            aria-expanded="true">Statistik Penduduk <span
                                                class="fs-sm text-muted ms-1">(22)</span>
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
                                            <li><span style="cursor:pointer" wire:click="statusPendidikan"
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
                                                    wire:click="bpjsKes"class="link-body">BPJS Kesehatan</span></li>
                                            <li><a span style="cursor:pointer"
                                                    wire:click="statusKehamilan"class="link-body">Status Kehamilan</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="mb-1">
                                    <h4> <a href="#" class="align-items-center rounded link-body"
                                            data-bs-toggle="collapse" data-bs-target="#keluarga-collapse"
                                            aria-expanded="false">Statistik Keluarga<span
                                                class="fs-sm text-muted ms-1">(1)</span>
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
                                            aria-expanded="false">Statistik Bantuan<span
                                                class="fs-sm text-muted ms-1">(2)</span>
                                        </a></h4>
                                    <div class="collapse mt-1" id="bantuan-collapse" style="">
                                        <ul class="btn-toggle-nav list-unstyled ps-2">
                                            <li><span style="cursor:pointer"
                                                    wire:click="bantuanPenduduk"class="link-body">Penerima Bantuan
                                                    Penduduk</span></li>
                                            <li><span style="cursor:pointer" wire:click="bantuaKeluarga"
                                                    class="link-body">Penerima Bantuan Keluarga</span></li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="mb-1">
                                    <h4> <a href="#" class="align-items-center rounded collapsed link-body"
                                            data-bs-toggle="collapse" data-bs-target="#rtm-collapse"
                                            aria-expanded="false"> Statistik RTM <span
                                                class="fs-sm text-muted ms-1">(1)</span>
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
                @endif
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container -->
    </section>
</div>
