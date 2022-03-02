<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use Validator;

class ItemController extends Controller
{
    public function index()
    {
        $item = Item::get();
        if ($item) {
            $response = [
                'code' => 200,
                'status' => "success",
                'message' => "Data ditemukan",
                'data' => $item
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
        ]);

        if ($validator->fails()) {
            $output = [
                'code' => 400,
                'status' => 'error',
                'message' => $validator->errors()->first()
            ];
            return response()->json($output, 400);
        }
        
        $item = Item::where('nama', $request->nama)->first();
        if($item) {
            $output = [
                'code' => 400,
                'status' => 'error',
                'message' => ucwords($request->nama). ' sudah tersedia'
            ];
            return response()->json($output, 400);
        }

        $item = new Item;
        $item->nama = $request->nama;
        $item->save();

        $response = [
            'code' => 200,
            'status' => "success",
            'message' => "Data berhasil disimpan",
            'data' => $item
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

        $item = Item::find($id);
        if ($item) {
            $item->nama = $request->nama;
            $item->save();
            $response = [
                'code' => 200,
                'status' => "success",
                'message' => "Data berhasil diupdate",
                'data' => $item
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
        $item = Item::find($id);
        if ($item) {
            Item::where('id',$id)->delete();
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
