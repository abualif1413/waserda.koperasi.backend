<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PenerimaanKas extends Model
{
    protected $table = "itbl_penerimaan_kas";
    protected $primaryKey = "id";
    const CREATED_AT = 'insert_time';
    const UPDATED_AT = 'update_time';
}
