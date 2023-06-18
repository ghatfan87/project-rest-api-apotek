<?php

namespace App\Http\Controllers;

use App\Helpers\ApiFormatter;
use App\Models\Apotek;
use Illuminate\Http\Request;
use Exception;

class ApotekController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // ambil data dari key search_nama bagian params yg postman
        $search = $request->search_apoteker;
        // ambil data dari key limit bagian params nya postman
        $limit = $request->limit;
        // ambil semua data melalui model
        $apotek = Apotek::where('apoteker', 'LIKE', '%' . $search . '%')->limit($limit)->get();
        if ($apotek) {
            // kalau data berhasil diambil
            return ApiFormatter::createdAPI(200, 'success', $apotek);
        } else {
            return ApiFormatter::createdAPI(400, 'failed');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function createToken()
    {
        return csrf_token();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama' => 'required|min:4',
                'rujukan' => 'required',
                'rumah_sakit' =>  $request->rujukan == 1 ? 'required' : '',
                'obat' => 'required',
                'harga_satuan' => 'required',
                'apoteker' => 'required',
            ]);
            $harga_satuan = explode(',', $request->harga_satuan);
            $obat = explode(',', $request->obat);
            $total_harga = 0;
            foreach ($harga_satuan as $harga) {
                $harga = (int) trim($harga, '"');
                $total_harga += $harga;
            }

            if (count($harga_satuan) != count($obat)) {
                return ApiFormatter::createdAPI(400, 'failed', 'Jumlah obat dan Harga Satuan tidak sama');
            }

            $apotek = Apotek::create([
                'nama' => $request->nama,
                'rujukan' => $request->rujukan,
                'rumah_sakit' => $request->rujukan == 1 ? $request->rumah_sakit : null,
                'obat' => $request->obat,
                'harga_satuan' => $request->harga_satuan,
                'total_harga' => $total_harga,
                'apoteker' => $request->apoteker,
            ]);

            $hasilTambahData = Apotek::where('id', $apotek->id)->first();
            if ($hasilTambahData) {
                return ApiFormatter::createdAPI(200, 'success', $apotek);
            } else {
                if ($hasilTambahData) {
                    return ApiFormatter::createdAPI(200, 'success', $apotek);
                }
                return ApiFormatter::createdAPI(400, 'failed');
            }
        } catch (Exception $error) {
            return ApiFormatter::createdAPI(400, 'failed', $error->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $apotek = Apotek::find($id);
            if ($apotek) {
                return ApiFormatter::createdAPI(200, 'success', $apotek);
            } else {
                return ApiFormatter::createdAPI(400, 'failed');
            }
        } catch (Exception $error) {
            return ApiFormatter::createdAPI(400, 'failed', $error->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'nama' => 'required|min:4',
                'rujukan' => 'required',
                'rumah_sakit' => $request->rujukan == 1 ? 'required' : '',
                'obat' => 'required',
                'harga_satuan' => 'required',
                'apoteker' => 'required',
            ]);

            $apotek = Apotek::findorFail($id);
            $harga_satuan = explode(',', $request->harga_satuan);
            $total_harga = array_reduce($harga_satuan, function ($carry, $harga) {
                return $carry + (int)trim($harga, '"');
            }, 0);

            $apotek->update([
                'nama' => $request->nama,
                'rujukan' => $request->rujukan,
                'rumah_sakit' => $request->rujukan  == 1 ? $request->rumah_sakit : null,
                'obat' => $request->obat,
                'harga_satuan' => $request->harga_satuan,
                'total_harga' => $total_harga,
                'apoteker' => $request->apoteker,
            ]);
            $updateStudent = Apotek::where('id', $apotek->id)->first();
            if ($updateStudent) {
                return ApiFormatter::createdAPI(200, 'success', $updateStudent);
            } else {
                return ApiFormatter::createdAPI(400, 'failed');
            }
        } catch (Exception $error) {
            return ApiFormatter::createdAPI(400, 'failed', $error->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $apotek = Apotek::findOrFail($id);
            $proses = $apotek->delete();

            if ($proses) {
                return ApiFormatter::createdAPI(200, 'success delete data!');
            } else {
                return ApiFormatter::createdAPI(400, 'Failed');
            }
        } catch (Exception $error) {
            return ApiFormatter::createdAPI(400, 'Failed', $error->getMessage());
        }
    }

    public function deletePermanent($id)
    {
        try {
            $apotek = Apotek::onlyTrashed()->where('id', $id);
            $proses = $apotek->forceDelete();

            if ($proses) {
                return ApiFormatter::createdAPI(200, 'success delete data!');
            } else {
                return ApiFormatter::createdAPI(400, 'Failed');
            }
        } catch (Exception $error) {
            return ApiFormatter::createdAPI(400, 'Failed', $error->getMessage());
        }
    }

    public function onlyTrash(Request $request)
    {
        try {
            $search = $request->search_apoteker;
            // ambil data dari key limit bagian params nya postman
            $limit = $request->limit;
            // ambil semua data melalui model
            $apotek = Apotek::where('apoteker', 'LIKE', '%' . $search . '%')->limit($limit)->get();
            $apotek = Apotek::onlyTrashed()->get();
            if ($apotek) {
                return ApiFormatter::createdAPI(200, 'success', $apotek);
            } else {
                return ApiFormatter::createdAPI(400, 'failed');
            }
        } catch (Exception $error) {
            return ApiFormatter::createdAPI(400, 'failed', $error->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            $apotek = Apotek::onlyTrashed()->where('id', $id);
            $apotek->restore();
            $dataKembali = Apotek::where('id', $id)->first();
            if ($dataKembali) {
                return ApiFormatter::createdAPI(200, 'success', $dataKembali);
            } else {
                return ApiFormatter::createdAPI(400, 'Failed');
            }
        } catch (Exception $error) {
            return ApiFormatter::createdAPI(400, 'Failed', $error->getMessage());
        }
    }
}
