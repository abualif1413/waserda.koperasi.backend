<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PengeluaranKasDetail extends Model
{
    protected $table = "itbl_pengeluaran_kas_detail";
    protected $primaryKey = "id";
    const CREATED_AT = 'insert_time';
    const UPDATED_AT = 'update_time';
}
