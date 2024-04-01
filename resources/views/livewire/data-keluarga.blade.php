<div>
    <div class="col-xl-12 col-lg-12 col-md-6">
        <div class="card">
            <!-- <div class="card-header">
                    <h4 class="card-title">Pilih Kecamatan</h4>
                </div> -->
            <div class="card-body">
                <div class="basic-form">
                    <div class="row">
                        <div class="col-lg-6">
                            {!! Form::select('kecamatan', $kecamatan, null, [
                                'placeholder' => 'Silahkan Pilih Kecamatan',
                                'class' => 'form-control wide mb-3',
                                'wire:model.live' => 'kecTerpilih',
                            ]) !!}
                        </div>
                        <div class="col-lg-6">
                            {!! Form::select('desa', $desa, null, [
                                'placeholder' => 'Silahkan Pilih Desa',
                                'class' => 'form-control wide mb-3',
                                'wire:model.live' => 'desTerpilih',
                            ]) !!}
                        </div>
                        <div class="col-lg-6">
                            {!! Form::select('dusun', $dusun, null, [
                                'placeholder' => 'Silahkan Pilih Dusun',
                                'class' => 'form-control wide mb-3',
                                'wire:model.live' => 'dusTerpilih',
                            ]) !!}
                        </div>
                        <div class="col-lg-6">
                            {!! Form::select('rw', $rw, null, [
                                'placeholder' => 'Silahkan Pilih RW',
                                'class' => 'form-control wide mb-3',
                                'wire:model.live' => 'rwTerpilih',
                            ]) !!}
                        </div>
                        <div class="col-lg-6">
                            {!! Form::select('rt', $rw, null, [
                                'placeholder' => 'Silahkan Pilih RT',
                                'class' => 'form-control wide mb-3',
                                'wire:model.live' => 'rtTerpilih',
                            ]) !!}
                        </div>
                    </div>
                    <div class="row">
                        <table id="jmbt" class="table display" style="min-width: 845px">
                            <thead class="text-center">
                                <tr>
                                    <th>Nama</th>
                                    <th>Tempat Lahir</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Alamat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($penduduk as $user)
                                    <tr>
                                        <td>{{ $user->nama }}</td>
                                        <td class="text-center">{{ strtoupper($user->tempatlahir) }}</td>
                                        <td class="text-center">
                                            {{ \Carbon\Carbon::parse($user->tanggallahir)->format('d/m/Y') }}</td>
                                        <td class="text-center">
                                            {{ 'RT ' . ($user->alamate->rt ?? 'N/A') . ' RW ' . ($user->alamate->rw ?? 'N/A') . ' DUSUN ' . ($user->alamate->dusun ?? 'N/A') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="text-center">
                                        <td colspan="4" class="text-center">TIDAK ADA DATA</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $penduduk->links('vendor.livewire.bootstrap') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <!-- <script>
        document.addEventListener('livewire:load', function() {
            $('#example').DataTable();
        });
    </script> -->
@endpush
