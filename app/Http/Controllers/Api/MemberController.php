<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Member;

class MemberController extends Controller
{
    //
    public function login(Request $request)
    {
        # code...
        $member = Member::
                    whereRaw('email = ? AND passwd = MD5(?)', [$request->email, $request->password])
                    ->get();
        if(count($member) == 0) {
            return \response()->json(["success" => 0, "message" => "Username dan password tidak ditemukan"]);
        } else {
            $token = [
                "user_id" => $member[0]->id,
                "user_name" => $member[0]->nm_lengkap,
                "foto" => $member[0]->photo,
                "user_token" => $member[0]->kode_aktivasi
            ];
            return \response()->json(["success" => 1, "message" => "Login berhasil", "token" => $token]);
        }
    }

    public function getUserByToken($token) {
        if($token) {
            $member = Member::
                    where('kode_aktivasi', $token)
                    ->get();
            if(count($member) > 0)
                return response()->json(["success" => 1, "data" => $member[0]]);
            else
                return response()->json(["success" => 1, "data" => ["nm_lengkap" => "-", "email" => "-"]]);    
        } else {
            return response()->json(["success" => 1, "data" => ["nm_lengkap" => "-", "email" => "-"]]);
        }
    }
}
