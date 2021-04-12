<?php

namespace App\Http\Controllers;

use App\Models\Database;
use Illuminate\Http\Request;

class WebOrderDetailController extends Controller
{
    public function show($id)
    {
        $database = Database::query()->where('id', $id)->firstOrFail();
        dd($database);
    }
}
