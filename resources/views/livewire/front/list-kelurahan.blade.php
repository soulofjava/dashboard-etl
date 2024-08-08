<div>
    <section class="wrapper bg-soft-primary">
        <div class="container pt-10 pb-19 pt-md-5 pb-md-5 text-center">
            <div class="row">
                <div class="col-md-7 col-lg-6 col-xl-5 mx-auto">
                    <h1 class="display-1 mb-3">Kelurahan Paling Aktif</h1>
                    <p class="lead px-lg-5 px-xxl-8">Kelurahan yang sudah mengisi data & pengujung terbanyak.</p>
                </div>
                <div class="blog grid grid-view">
                    <div class="row isotope gx-md-8 gy-8 mb-8">
                        @foreach ($kelurahan as $k)

                            <article class="item post col-md-4">
                                <div class="card">
                                    <figure class="card-img-top overlay overlay-1 hover-scale"><a href="#"> <img
                                                src="{{ asset('costum/img') }}./kelurahan-1.jpg"
                                                style="object-fit:cover; object-position: right; width:200px; height:300px;"
                                                alt="" /></a>
                                        <figcaption>
                                            <h5 class="from-top mb-0">Read More</h5>
                                        </figcaption>
                                    </figure>
                                    <div class="card-body">
                                        <div class="post-header">
                                            <div class="post-category uil uil-link">
                                                <a href="{{ $k->website }}" class="hover" rel="category"
                                                    target="_blank">{{ $k->nama_desa }}</a>
                                            </div>
                                            <!-- /.post-category -->

                                            <!-- /.post-header -->
                                            <div class="post-meta" style="font-size: medium; text-align:left;">
                                                <p>{{ $k->alamat_kantor }}</p>
                                            </div>
                                            <!-- /.post-content -->
                                        </div>
                                        <!--/.card-body -->
                                        <div>
                                            <ul class="post-meta">
                                                <li class="post-date"><i class="uil uil-users-alt"></i><span>
                                                        {{ $k->penduduk_count }} Penduduk</span>
                                                </li>
                                                <li class="post-comments"><a href="#"><i
                                                            class="uil uil-chat-bubble-user"></i>{{ $k->keluarga_count }}
                                                        Keluarga</a>
                                                </li>
                                                <li class="post-likes ms-auto"><a href="#"><i
                                                            class="uil uil-heart-alt"></i>{{ $k->rtm_count}} RTM</a>
                                                </li>
                                            </ul>
                                            <!-- /.post-meta -->
                                        </div>
                                        <!-- /.card-footer -->
                                    </div>
                                    <!-- /.card -->
                            </article>
                        @endforeach

                        <!-- /.post -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /column -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container -->
    </section>
</div>
