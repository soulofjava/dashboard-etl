<div>

    <div class="container pt-8 pt-md-14">
        <div class="row gx-lg-0 gx-xl-8 gy-10 gy-md-13 gy-lg-0 mb-7 mb-md-10 mb-lg-16 align-items-center">
            <div class="col-md-8 offset-md-2 col-lg-6 offset-lg-1 position-relative order-lg-2" data-cue="zoomIn">
                <div class="shape bg-dot primary rellax w-17 h-19" data-rellax-speed="1"
                    style="top: -1.7rem; left: -1.5rem;"></div>
                <div class="shape rounded bg-soft-primary rellax d-md-block" data-rellax-speed="0"
                    style="bottom: -1.8rem; right: -0.8rem; width: 85%; height: 90%;"></div>
                <figure class="rounded"><img
                        src="{{ asset('costum/img/foto_8c856619-9900-423b-bb2d-dd7a65ab7a24.jpg') }}"
                        srcset="./assets/img/photos/about7@2x.jpg 2x" alt="" /></figure>
            </div>
            <!--/column -->
            <div class="col-lg-5 mt-lg-n10 text-center text-lg-start" data-cues="slideInDown" data-group="page-title"
                data-delay="600">
                <h1 class="display-1 mb-5">Kami menghadirkan solusi untuk membuat hidup lebih mudah.</h1>
                <p class="lead fs-25 lh-sm mb-7 px-md-10 px-lg-0">
                    Dashboard data desa menampilkan data dari seluruh desa dan kelurahan kabupaten Wonosobo tentang
                    Sosial Ekonomi, Kependudukan, Pemerintahan, Keuangan dan lain-lain secara realtime.</p>
            </div>
            <!--/column -->
        </div>
    </div>
    <section class="wrapper image-wrapper bg-image bg-overlay bg-overlay-300 text-white"
        data-image-src="{{ asset('sandbox/assets/img/photos/bg23.png') }}">
        <div class="container pb-15 pb-md-17 mt-10">
            <div class="row gx-md-5 gy-5 text-center">
                <div class="col-md-6 col-xl-3">
                    <div class="card shadow-lg">
                        <div class="card-body">
                            <img src="{{ asset('costum/img/penduduk.jpg') }}"
                                class="rounded svg-inject icon-svg icon-svg-sm solid-mono text-fuchsia mb-3"
                                alt="" />
                            <h4>Penduduk</h4>
                            <h5 class="mb-2">{{ $totalPenduduk ?? '-' }}</h5>
                        </div>
                        <!--/.card-body -->
                    </div>
                    <!--/.card -->
                </div>
                <!--/column -->
                <div class="col-md-6 col-xl-3">
                    <div class="card shadow-lg">
                        <div class="card-body">
                            <img src="{{ asset('costum/img/keluarga.jpg') }}"
                                class="svg-inject icon-svg icon-svg-sm solid-mono text-violet mb-3" alt="" />
                            <h4>Kecamatan</h4>
                            <h5 class="mb-2">{{ $totalKecamatan }}</h5>
                        </div>
                        <!--/.card-body -->
                    </div>
                    <!--/.card -->
                </div>
                <!--/column -->
                <div class="col-md-6 col-xl-3">
                    <div class="card shadow-lg">
                        <div class="card-body">
                            <img src="{{ asset('costum/img/rtm.jpg') }}"
                                class="svg-inject icon-svg icon-svg-sm solid-mono text-violet mb-3" alt="" />
                            <h4>Desa / Kelurahan</h4>
                            <h5 class="mb-2">{{ $totalDesa }}</h5>
                        </div>
                        <!--/.card-body -->
                    </div>
                    <!--/.card -->
                </div>
                <!--/column -->
                <div class="col-md-6 col-xl-3">
                    <div class="card shadow-lg">
                        <div class="card-body">
                            <img src="{{ asset('costum/img/bantuan.jpg') }}"
                                class="svg-inject icon-svg icon-svg-sm solid-mono text-violet mb-3" alt="" />
                            <h4>Bantuan</h4>
                            <h5 class="mb-2">{{ $totalBantuan }}</h5>
                        </div>
                        <!--/.card-body -->
                    </div>
                    <!--/.card -->
                </div>
                <!--/column -->
            </div>
            <br>

        </div>
    </section>
    <livewire:front.statistik>
        <livewire:front.list-kelurahan>
</div>
