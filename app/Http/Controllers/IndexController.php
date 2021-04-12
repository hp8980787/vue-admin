<?php

namespace App\Http\Controllers;

use App\Models\Database;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use mysql_xdevapi\Exception;
use function Ramsey\Uuid\v1;

class IndexController extends Controller
{
    public function index()
    {
        $countries = Database::query()->selectRaw('any_value(id)as id,any_value(nickname)as nickname')->groupBy('nickname')
            ->get()->pluck('nickname', 'nickname');

        return view('index.index', compact('countries'));

    }
}
