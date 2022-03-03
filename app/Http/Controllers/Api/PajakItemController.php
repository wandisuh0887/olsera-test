<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PajakItem;
use App\Models\Item;
use Validator, DB;

class PajakItemController extends Controller
{
    public function index()
    { 
        
        // $itempajak = DB::table('pajak_item as pi')
        //                     ->select('i.id','i.nama', DB::Raw('concat("[", group_concat(json_object("id", pi.id, "nama", p.nama, "rate", concat(floor(p.rate),"%"))), "]") as pajak'))
        //                     ->join('pajak as p', 'pi.pajak_id', '=', 'p.id')
        //                     ->join('item as i', 'pi.item_id', '=', 'i.id')
        //                     ->groupBy('i.id')
        //                     ->get();

        $itempajak = Item::with('pajak')->get();
        
        if ($itempajak) {
            $response = [
                'code' => 200,
                'status' => "success",
                'message' => "Data ditemukan",
                'data' => $itempajak
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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_id' => 'required',
            'pajak_id' => 'required',
        ]);

        if ($validator->fails()) {
            $output = [
                'code' => 400,
                'status' => 'error',
                'message' => $validator->errors()->first()
            ];
            return response()->json($output, 400);
        }
        
        $itempajak = PajakItem::where('item_id', $request->item_id)->where('pajak_id', $request->pajak_id)->first();
        if($itempajak) {
            $output = [
                'code' => 400,
                'status' => 'error',
                'message' => 'Pajak item sudah tersedia'
            ];
            return response()->json($output, 400);
        }

        $itempajak = new PajakItem;
        $itempajak->item_id = $request->item_id;
        $itempajak->pajak_id = $request->pajak_id;
        $itempajak->save();

        $response = [
            'code' => 200,
            'status' => "success",
            'message' => "Data berhasil disimpan",
            'data' => $itempajak
        ];
        return response()->json($response, 201);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'item_id' => 'required',
            'pajak_id' => 'required',
        ]);

        if ($validator->fails()) {
            $output = [
                'code' => 400,
                'status' => 'error',
                'message' => $validator->errors()->first()
            ];
            return response()->json($output, 400);
        }

        $itempajak = PajakItem::find($id);
        if ($itempajak) {
            $cek = PajakItem::where('item_id', $request->item_id)->where('pajak_id', $request->pajak_id)->first();
            if($cek) {
                $output = [
                    'code' => 400,
                    'status' => 'error',
                    'message' => 'Pajak item sudah tersedia'
                ];
                return response()->json($output, 400);
            }
            
            $itempajak->item_id = $request->item_id;
            $itempajak->pajak_id = $request->pajak_id;
            $itempajak->save();
            $response = [
                'code' => 200,
                'status' => "success",
                'message' => "Data berhasil diupdate",
                'data' => $itempajak
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
        $itempajak = PajakItem::find($id);
        if ($itempajak) {
            PajakItem::where('id',$id)->delete();
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
