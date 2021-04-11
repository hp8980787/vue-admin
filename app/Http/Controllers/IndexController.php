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
        $databases = Database::query()->with(['remotetable' => function ($query) {
            $query->where('type_id', 2);
        }])->where('nickname', 'like', "%德国%")->get()->toArray();
//        dd($databases);
        $count = 0;
        $time = Carbon::now();
        $time1 = clone $time;
        $lastMonthFirstDay = $time->subMonth()->firstOfMonth();
        $lastMonthLastDay = $time1->subMonth()->lastOfMonth();

        foreach ($databases as $key => $database) {
            Config::set('database.connections.choose', ['driver' => 'mysql',
                'host' => $database['ip'],
                'port' => $database['port'],
                'database' => $database['database'],
                'username' => $database['username'],
                'password' => $database['passwd'],]);

            for ($i = 0; $i < sizeof($database['remotetable']); $i++) {
                if (in_array('created_at', json_decode($database['remotetable'][$i]['full_column']))) {
                    $time_column = 'created_at';
                } else if (in_array('dd_time', json_decode($database['remotetable'][$i]['full_column']))) {
                    $time_column = 'dd_time';
                }
//                if ($key==4){
//                    dd(config('database.connections.choose'),$key,$database['remotetable'][$i]['name']);
//                }
//                $count = DB::connection('choose');
                dump($count);
//                $count = DB::connection('choose')->table($database['remotetable'][$i]['name'])
//                        ->whereBetween($time_column,[$lastMonthFirstDay,$lastMonthLastDay])->latest($time_column)->get();
//                if ($key==3){
//                    dump(\config('database.connections.choose'));
//                }

                dump($key);
            }
//            foreach ($database['remotetable'] as $table) {
//
//                if (in_array('created_at', json_decode($table['full_column']))) {
//                    $time_column = 'created_at';
//                } else if (in_array('dd_time', json_decode($table['full_column']))) {
//                    $time_column = 'dd_time';
//                }
//                dump($table['name'], $database['remotetable']);
//
//                echo "<br>";
////                $count = DB::connection('choose')->table($table['name'])
////                        ->whereBetween($time_column,[$lastMonthFirstDay,$lastMonthLastDay])->latest($time_column)->get();
//
////                Log::info($database['url'],$count->toArray());
//            }


//        $countries = Database::query()->selectRaw('any_value(id)as id,any_value(nickname)as nickname')->groupBy('nickname')
//            ->get()->pluck('nickname', 'nickname');
//
//        return view('index.index', compact('countries'));
        }
    }
}
