<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $connection = 'choose';
    public function __construct(array $attributes = [])
    {
        $choose = session()->get('choose');
        $this->table = $choose['order_table'];
        $this->fillable = json_decode($choose['order_full_column']);
        $this->primaryKey = json_decode($choose['order_full_column'])[0];
        parent::__construct($attributes);
    }

}
