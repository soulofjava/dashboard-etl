<div>
    <div class="col-xl-12 col-lg-12 col-md-6">
        <div class="card">
            <!-- <div class="card-header">
                    <h4 class="card-title">Pilih Kecamatan</h4>
                </div> -->
            <div class="card-body">
                <div class="basic-form">
                    <form>
                        <div class="row">
                            <div class="col-lg-6">
                                {!! Form::select('kecamatan', $kecamatan, null,['placeholder' => 'Silahkan Pilih
                                Kecamatan', 'class'=>'default-select form-control wide mb-3']); !!}
                            </div>
                            @dump($selectedKecamatan)
                            @dump($kecamatan)
                            <div class="col-lg-6">
                                {!! Form::select('desa', $desa, null,['placeholder' => 'Silahkan Pilih Desa',
                                'class'=>'default-select form-control wide mb-3']); !!}
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>