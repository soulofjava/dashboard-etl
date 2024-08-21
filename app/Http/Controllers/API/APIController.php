<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\TwebPenduduk;
use Illuminate\Http\Request;

class APIController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $nama = $request->query('nama');
        $nik = $request->query('nik');

        $result = TwebPenduduk::where('nik', 'LIKE', "%{$nik}%")->where('nama', 'LIKE', "%{$nama}%")->get();

        if ($result->isNotEmpty()) {
            return response()->json([
                'status' => 'success',
                'data' => $result
            ], 200); // 200 adalah kode status HTTP untuk "OK"
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Data Tidak Ditemukan'
            ], 404); // 404 adalah kode status HTTP untuk "Not Found"
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
