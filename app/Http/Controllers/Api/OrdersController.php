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

        $databases = Database::query()->with(['remotetable' => function ($query) {
            $query->where('type_id', 2);
        }])->where('nickname', 'like', "%{$country}%")->get();
        $countryOrdersCounts = 0;
        foreach ($databases as $database) {
            Config::set('database.connections.choose', ['driver' => 'mysql',
                'host' => $database->ip,
                'port' => $database->port,
                'database' => $database->database,
                'username' => $database->username,
                'password' => $database->passwd,]);
            DB::purge('choose');

            DB::reconnect('choose');
            $count = 0;
            foreach ($database->remotetable as $table) {

                if (in_array('created_at', json_decode($table->full_column))) {
                    $time_column = 'created_at';
                } else if (in_array('dd_time', json_decode($table->full_column))) {
                    $time_column = 'dd_time';
                }

                $res = DB::connection('choose')->table($table->name);
                switch ($type) {
                    case 1:
                        $time = Carbon::now();
                        $time = $time->subDay(1);
                        $res->where($time_column, '>=', $time);
                        break;
                    case 2:
                        $now = Carbon::now();
                        $before = Carbon::now()->subMonth()->lastOfMonth();
                        $res->where($time_column,'>',$before)->where($time_column,'<=',$now);
                        break;

                    case 3:
                        $before = Carbon::now()->subMonth()->firstOfMonth();
                        $now = Carbon::now()->subMonth()->lastOfMonth();
                        $res->whereBetween($time_column, [$before, $now]);
                        break;
                    case 4:
                        $time = Carbon::now();
                        $time = $time->subDay(1);
                        $res->where($time_column, '>', $time);
                        break;
                }
                $res->select();

                $count = $count + $res->count();
                $countryOrdersCounts = $countryOrdersCounts + $res->count();
            }
            $array['url'] = $database['url'];
            $array['count'] = $count;
            $array['database_id'] = $database['id'];
            $data[] = $array;
        }
        return response(['data' => $data, 'counts' => $countryOrdersCounts], 200);

    }

    public function web(Request $request)
    {
        $country = $request->country;
        $databases = Database::query()->with(['remotetable' => function ($query) {
            $query->where('type_id', 2);
        }])->where('nickname', 'like', "%{$country}%")
            ->get();
        $time = Carbon::now();
        $time = $time->subDay(1);
        $countryOrdersCounts = 0;
        foreach ($databases as $database) {
            Config::set('database.connections.choose', ['driver' => 'mysql',
                'host' => $database->ip,
                'port' => $database->port,
                'database' => $database->database,
                'username' => $database->username,
                'password' => $database->passwd,]);
            DB::purge('choose');

            DB::reconnect('choose');
            $count = 0;
            foreach ($database->remotetable as $table) {

                if (in_array('created_at', json_decode($table->full_column))) {
                    $time_column = 'created_at';
                } else if (in_array('dd_time', json_decode($table->full_column))) {
                    $time_column = 'dd_time';
                }
                $res = DB::connection('choose')->table($table->name)
                    ->where($time_column, '>', $time)->select();
                $count = $count + $res->count();
                $countryOrdersCounts = $countryOrdersCounts + $res->count();
            }
            $array['url'] = $database['url'];
            $array['count'] = $count;
            $array['database_id'] = $database['id'];
            $data[] = $array;
        }


        return response(['data' => $data, 'counts' => $countryOrdersCounts], 200);
    }
}
