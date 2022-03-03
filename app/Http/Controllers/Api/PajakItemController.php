<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pajak;
use App\Models\Item;
use Validator, DB;

class PajakItemController extends Controller
{
    public function index()
    {   
        $itempajak = DB::table('pajak_item as pi')
                            ->select('i.id','i.nama', DB::Raw('concat("[", group_concat(json_object("id", pi.id, "nama", p.nama, "rate", concat(floor(p.rate),"%"))), "]") as pajak'))
                            ->join('pajak as p', 'pi.pajak_id', '=', 'p.id')
                            ->join('item as i', 'pi.item_id', '=', 'i.id')
                            ->groupBy('i.id')
                            ->get();

        // $itempajak = Item::with('pajak')->get();

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

        $itempajak = Item::find($request->item_id);
        $itempajak->pajak()->syncWithoutDetaching(['item_id' => $request->item_id, 'pajak_id' => $request->pajak_id]);

        $response = [
            'code' => 200,
            'status' => "success",
            'message' => "Data berhasil disimpan",
            'data' => $itempajak->pajak
        ];
        return response()->json($response, 201);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'pajak_id_new' => 'required',    // id pajak baru
            'pajak_id' => 'required',   // id pajak old
        ]);

        if ($validator->fails()) {
            $output = [
                'code' => 400,
                'status' => 'error',
                'message' => $validator->errors()->first()
            ];
            return response()->json($output, 400);
        }

        $itempajak = Item::find($id);
        $itempajak->pajak()->updateExistingPivot($request->pajak_id_new, [
            'pajak_id' => $request->pajak_id,
        ]);
        if ($itempajak) {
            
            $response = [
                'code' => 200,
                'status' => "success",
                'message' => "Data berhasil diupdate",
                'data' => $itempajak->pajak
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

    public function destroy(Request $request, $id)
    {
        $itempajak = Item::find($id);
        if ($itempajak) {
            $itempajak->pajak()->detach($request->pajak_id);
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
