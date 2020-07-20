<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PengeluaranKas extends Model
{
    protected $table = "itbl_pengeluaran_kas";
    protected $primaryKey = "id";
    const CREATED_AT = 'insert_time';
    const UPDATED_AT = 'update_time';
}
