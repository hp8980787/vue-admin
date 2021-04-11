<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Database;
use App\Models\OrderFormat;

class Table extends Model
{
    protected $table = 'remote_tables';

    public function database()
    {
        return $this->belongsTo(Database::class, 'database_id', 'id');
    }

    public function format()
    {
        return $this->belongsTo(OrderFormat::class,'format_id','id');
    }

}
