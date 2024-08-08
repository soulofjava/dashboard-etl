<?php

if (!function_exists('get_kecamatan')) {
    function get_kecamatan()
    {
        return \App\Models\Config::select('kode_kecamatan', 'nama_kecamatan')->groupBy('kode_kecamatan', 'nama_kecamatan')->get();
    }
}
