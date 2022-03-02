<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pajak;
use Validator;

class PajakController extends Controller
{
    public function index()
    {
        $pajak = Pajak::get();
        if ($pajak) {
            $response = [
                'code' => 200,
                'status' => "success",
                'message' => "Data ditemukan",
                'data' => $pajak
            ];
            return response()->json($response, 201);
        }else {
            $response = [
                'code' => 400,
                'status' => "error",
                'message' => "Data tidak ditemukan"
            ];
            return response()->json($response, 400);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'rate' => 'required',
        ]);

        if ($validator->fails()) {
            $output = [
                'code' => 400,
                'status' => 'error',
                'message' => $validator->errors()->first()
            ];
            return response()->json($output, 400);
        }

        $pajak = Pajak::where('nama', $request->nama)->first();
        if($pajak) {
            $output = [
                'code' => 400,
                'status' => 'error',
                'message' => ucwords($request->nama). ' sudah tersedia'
            ];
            return response()->json($output, 400);
        }
        
        $pajak = new Pajak;
        $pajak->nama = $request->nama;
        $pajak->rate = $request->rate;
        $pajak->save();

        $response = [
            'code' => 200,
            'status' => "success",
            'message' => "Data berhasil disimpan",
            'data' => $pajak
        ];
        return response()->json($response, 201);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
        ]);

        if ($validator->fails()) {
            $output = [
                'code' => 400,
                'status' => 'error',
                'message' => $validator->errors()->first()
            ];
            return response()->json($output, 400);
        }
        
        $pajak = Pajak::find($id);
        if ($pajak) {
            $pajak->nama = $request->nama;
            $pajak->rate = $request->rate;
            $pajak->save();
            $response = [
                'code' => 200,
                'status' => "success",
                'message' => "Data berhasil diupdate",
                'data' => $pajak
            ];
            return response()->json($response, 201);
        }else {
            $response = [
                'code' => 400,
                'status' => "error",
                'message' => "Data tidak ditemukan"
            ];
            return response()->json($response, 400);
        }
    }

    public function destroy($id)
    {
        $pajak = Pajak::find($id);
        if ($pajak) {
            Pajak::where('id',$id)->delete();
            $response = [
                'code' => 200,
                'status' => "success",
                'message' => "Data berhasil dihapus"
            ];
            return response()->json($response, 200);
        }else {
            $response = [
                'code' => 400,
                'status' => "error",
                'message' => "Data tidak ditemukan"
            ];
            return response()->json($response, 400);
        }    
    }
}
