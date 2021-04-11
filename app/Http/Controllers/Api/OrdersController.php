<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Database;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
class OrdersController extends Controller
{
    public function index(Request $request)
    {
        $country = $request->country;
        $type = $request->type;
        $databases = Database::query()->with(['remotetable'=>function($query){
            $query->where('type_id',2);
        }])->where('nickname', 'like', "%{$country}%")
            ->get();
        $time = Carbon::now();
        $time = $time->subDay(1);
        foreach ($databases as $database) {
            Config::set('database.connections.choose', ['driver' => 'mysql',
                'host' => $database->ip,
                'port' => $database->port,
                'database' => $database->database,
                'username' => $database->username,
                'password' => $database->passwd,]);

        }
        foreach ($database->remotetable as $table){

            if (in_array('created_at',json_decode($table->full_column))){
                $time_column = 'created_at';
            }else if (in_array('dd_time',json_decode($table->full_column))){
                $time_column = 'dd_time';
            }
           ;
            $count = DB::connection('choose')->table($table->name)
                ->where($time_column,'>',$time)->get();
            dd($count);
            if (sizeof($count)>0){
                dd($count);
            }

        }


    }

    public function web(Request $request)
    {
        $country = $request->country;

        $res = Database::query()->where('nickname', 'like', "%{$country}%")
            ->get();
        return response($res, 200);
    }
}
