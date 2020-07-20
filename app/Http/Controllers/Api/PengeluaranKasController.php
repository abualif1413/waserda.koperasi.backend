<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\PengeluaranKas;
use App\PengeluaranKasDetail;
use Illuminate\Support\Facades\DB;

class PengeluaranKasController extends Controller
{
    public function addRincian(Request $request)
    {
        # code...
        $validatedData = $request->validate([
            'id_coa' => 'required|numeric',
            'jumlah' => 'required',
            'keterangan' => 'required'
        ]);

        $userToken = $request->bearerToken();

        if($request->submit_state == "insert")
        {
            $obj = new PengeluaranKasDetail;
            $obj->id_pengeluaran_kas = $request->id_penerimaan_kas ?? null;
            $obj->id_coa = $request->id_coa;
            $obj->jumlah = $request->jumlah;
            $obj->keterangan = $request->keterangan;
            $obj->insert_user = $userToken;
            $obj->save();
        }
        else
        {
            $obj = PengeluaranKasDetail::find($request->id);
            $obj->id_coa = $request->id_coa;
            $obj->jumlah = $request->jumlah;
            $obj->keterangan = $request->keterangan;
            $obj->update_user = $userToken;
            $obj->save();
        }

        return \response()->json(["success" => 1, "message" => "Berhasil menambah data pengeluaran kas"]);
    }

    public function rincianList(Request $request, $id_pengeluaran_kas)
    {
        $userToken = $request->bearerToken();
        $pkd = [];
        if($id_pengeluaran_kas == 0)
        {
            $pkd = DB::select(
                '
                    SELECT
                        pkd.id, pkd.id_pengeluaran_kas, pkd.id_coa, pkd.jumlah, pkd.keterangan,
                        coa.acc_number, coa.acc_name
                    FROM
                        itbl_pengeluaran_kas_detail pkd
                        LEFT JOIN itbl_coa coa ON pkd.id_coa = coa.id
                    WHERE
                        pkd.id_pengeluaran_kas IS NULL AND pkd.insert_user = ?
                    ORDER BY
                        pkd.id ASC
                ', [$userToken]
            );
        }
        else
        {
            $pkd = DB::select(
                '
                    SELECT
                        pkd.id, pkd.id_pengeluaran_kas, pkd.id_coa, pkd.jumlah, pkd.keterangan,
                        coa.acc_number, coa.acc_name
                    FROM
                        itbl_pengeluaran_kas_detail pkd
                        LEFT JOIN itbl_coa coa ON pkd.id_coa = coa.id
                    WHERE
                        pkd.id_pengeluaran_kas = ?
                    ORDER BY
                        pkd.id ASC
                ',
                [$id_pengeluaran_kas]
            );
        }

        return \response()->json(["data" => $pkd]);
    }

    public function findRincian($id)
    {
        # code...
        $obj = PengeluaranKasDetail::find($id);

        return \response()->json($obj);
    }

    public function deleteRincian($id)
    {
        # code...
        $obj = PengeluaranKasDetail::find($id);
        $obj->delete();

        return \response()->json(["success" => 1, "message" => "Berhasil menghapus rincian pengeluaran kas"]);
    }

    public function addHeader(Request $request)
    {
        # code...
        $userToken = $request->bearerToken();
        $validatedData = $request->validate([
            'id_akun_debet' => 'required',
            'tanggal' => 'required'
        ]);

        if(!$request->id_pengeluaran_kas)
        {
            $objHeader = new PengeluaranKas;
            $objHeader->id_akun_kredit = $request->id_akun_debet;
            $objHeader->tanggal = $request->tanggal;
            $objHeader->insert_user = $userToken;
            $objHeader->save();

            $id_pengeluaran_kas = $objHeader->id;

            DB::table('itbl_pengeluaran_kas_detail')
                ->whereNull('id_pengeluaran_kas')
                ->where('insert_user', $userToken)
                ->update(['id_pengeluaran_kas' => $id_pengeluaran_kas]);
        }
        else
        {
            $objHeader = PengeluaranKas::find($request->id_pengeluaran_kas);
            $objHeader->id_akun_kredit = $request->id_akun_debet;
            $objHeader->tanggal = $request->tanggal;
            $objHeader->update_user = $userToken;
            $objHeader->save();
        }
        

        return \response()->json(["success" => 1, "message" => "Pengeluaran kas telah disimpan"]);
    }

    public function headerList(Request $request)
    {
        # code...
        $like = "%" . $request->src . "%";
        $pk = DB::select(
            "
                SELECT
                    pk.id, LPAD(pk.id,6,'0') AS kode, MAX(pk.tanggal) AS tanggal,
                    MAX(coa.acc_number) AS acc_number, MAX(coa.acc_name) AS acc_name,
                    SUM(pkd.jumlah) AS jumlah,
                    GROUP_CONCAT(pkd.keterangan ORDER BY pkd.id SEPARATOR '; ') AS keterangan
                FROM
                    itbl_pengeluaran_kas pk
                    LEFT JOIN itbl_pengeluaran_kas_detail pkd ON pk.id = pkd.id_pengeluaran_kas
                    LEFT JOIN itbl_coa coa ON pk.id_akun_kredit = coa.id
                WHERE
                    pk.tanggal BETWEEN ? AND ?
                    AND (
                        coa.acc_name LIKE ? OR coa.acc_number LIKE ? OR
                        pkd.jumlah LIKE ? OR
                        pkd.keterangan LIKE ?
                    )
                GROUP BY
                    pk.id
                ORDER BY
                    pk.tanggal DESC, pk.id DESC
            ", 
            [$request->dari, $request->sampai, $like, $like, $like, $like]
        );

        return \response()->json(["data" => $pk]);
    }

    public function findHeader($id_pengeluaran_kas)
    {
        # code...
        $objHeader = PengeluaranKas::find($id_pengeluaran_kas);

        return \response()->json($objHeader);
    }

    public function deleteHeader($id_pengeluaran_kas)
    {
        # code...
        $objHeader = PengeluaranKas::find($id_pengeluaran_kas);
        $objHeader->delete();

        return \response()->json(["success" => 1, "message" => "Pengeluaran kas telah dihapus"]);
    }
}
