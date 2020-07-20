<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\COA;
use App\KelompokCOA;
use Illuminate\Support\Facades\DB;

class COAController extends Controller
{
    public function getCOA(Request $request)
    {
        # code...
        $coa = DB::select(
            "
                SELECT
                    coa.*, kel.kelompok,
                    CASE
                        WHEN coa.tipe = 'B.DR' then 'Neraca - Debet'
                        WHEN coa.tipe = 'B.CR' then 'Neraca - Kredit'
                        WHEN coa.tipe = 'I.DR' then 'Laba/Rugi - Debet'
                        WHEN coa.tipe = 'I.CR' then 'Laba/Rugi - Kredit'
                    END AS tipe_logical
                FROM
                    itbl_coa coa
                    LEFT JOIN itbl_kelompok_coa kel ON coa.id_kelompok = kel.id
                WHERE
                    coa.acc_name LIKE ? OR coa.acc_number LIKE ? OR kel.kelompok LIKE ?
                ORDER BY
                    coa.logical_number ASC
            ",
            ['%' . $request->search . '%', '%' . $request->search . '%', '%' . $request->search . '%']
        );

        return \response()->json(["data" => $coa]);
    }

    public function getKelompokCOA(Request $request)
    {
        $kelompokCOA = KelompokCOA::orderBy('id', 'asc')->get();

        return response()->json(["data" => $kelompokCOA]);
    }

    public function addCOA(Request $request)
    {
        $validatedData = $request->validate([
            'id_kelompok' => ['required'],
            'acc_number' => ['required'],
            'acc_name' => ['required']
        ]);

        if($validatedData)
        {
            if($request->submit_state == "insert")
            {
                $coa = new COA;
                $coa->parent_id = $request->parent_id ?? 0;
                $coa->id_kelompok = $request->id_kelompok;
                $coa->acc_number = $request->acc_number;
                $coa->acc_name = $request->acc_name;
                $coa->save();
            }
            else
            {
                $coa = COA::find($request->id);
                $coa->parent_id = $request->parent_id ?? 0;
                $coa->id_kelompok = $request->id_kelompok;
                $coa->acc_number = $request->acc_number;
                $coa->acc_name = $request->acc_name;
                $coa->save();
            }
            

            return \response()->json(["success" => 1, "message" => "Data coa berhasil disimpan"]);
        }
        else
        {
            return \response()->json(["success" => 0, "message" => $errors->all()]);
        }
    }

    public function deleteCOA($id)
    {
        $coa = COA::find($id);
        $coa->delete();

        return \response()->json(["success" => 1, "message" => "Data coa berhasil dihapus"]);
    }

    function findCOA($id) {
        $coa = COA::find($id);

        return \response()->json($coa);
    }
}
