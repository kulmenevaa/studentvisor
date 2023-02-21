<?php

namespace App\Actions;

use Carbon\Carbon;
use App\Models\Student;
use App\Models\Settings;
use App\Models\Verified;
use App\Models\Statistic;
use App\Models\Suspicious;
use App\Helpers\ArrayHelper;
use App\Helpers\PeriodHelper;
use App\Helpers\LocationHelper;
use Illuminate\Support\Facades\Cache;

class StatisticAction
{
    private $ip_verified;
    private $cache_expiration;

    public function __construct() 
    {
        $this->ip_verified = Verified::select('ip')->get(); // проверенные ip
        $this->cache_expiration = Carbon::now()->addDay(1); // время действия кэша
    }
    
    private function getSettings($type) 
    {
        $settings = Settings::where([['status', 1], ['type', $type]])
            //['user_id', auth()->user()->id]
            ->whereNotNull(['amount', 'action', 'metering'])
            ->first();
        if(isset($settings)) {
            return $settings;
        }
        return exit();
    }

    private function generateName($request, $settings, $type) 
    {
        $str = implode('.', [
            $request->period,
            $settings->amount,
            $settings->action,
            $settings->metering
        ]);
        return $type.':'.$str;
    }
    
    public function getBreaking($request, $type) {
        $settings = self::getSettings($type);
        $name = self::generateName($request, $settings, $type);
        $period = PeriodHelper::set($request->period);
        $breaking = Cache::store('redis')->remember($name, $this->cache_expiration, function() use ($period, $settings) {
            $array = [];
            $unique_ip = Statistic::selectRaw('ip, count(distinct user) as count')
                ->where(function($query) use ($period) {
                    $query->where([['action', 'login'], ['result', 'fail']]);
                    $query->whereNotIn('ip', $this->ip_verified);
                    $query->whereBetween('date', $period);
                    $verified =  ArrayHelper::verifiedList($this->ip_verified);
                    if(!empty($verified)) {
                        $query->whereRaw($verified);
                    }
                })
                ->groupBy('ip')
                ->havingRaw('count(distinct user) >= '.$settings->amount)
                ->orderBy('count', 'desc')
                ->get();
            foreach($unique_ip as $unique) {
                $record = Statistic::selectRaw('user, date, ip, category, info')
                    ->where([['action', 'login'], ['result', 'fail'], ['ip', $unique->ip]]) 
                    ->whereBetween('date', $period)
                    ->orderBy('date', 'asc')
                    ->get();
                $item = ArrayHelper::betweenDates($record, $unique->ip, $settings);
                if(!empty($item)) {
                    $array[] = $item;
                } 
            }
            return ArrayHelper::sortByCount($array);
        });
        return $breaking;
    }

    public function getPlunk($request, $type)
    {
        $settings = self::getSettings($type);
        $name = self::generateName($request, $settings, $type);
        $period = PeriodHelper::set($request->period);
        $plunk = Cache::store('redis')->remember($name, $this->cache_expiration, function() use ($period, $settings) {
            $array = [];
            $unique_ip = Statistic::selectRaw('ip, count(distinct user) as count')
                ->where(function($query) use ($period) {
                    $query->where([['action', 'login'], ['result', 'ok']]);
                    $query->whereNotIn('ip', $this->ip_verified);
                    $query->whereBetween('date', $period);
                    $verified =  ArrayHelper::verifiedList($this->ip_verified);
                    if(!empty($verified)) {
                        $query->whereRaw($verified);
                    }
                })
                ->groupBy('ip')
                ->havingRaw('count(distinct user) >= '.$settings->amount)
                ->orderBy('count', 'desc')
                ->get();
            foreach($unique_ip as $unique) {
                $record = Statistic::selectRaw('user, date, ip, category, info')
                    ->where([['action', 'login'], ['result', 'ok'], ['ip', $unique->ip]]) 
                    ->whereBetween('date', $period)
                    ->orderBy('date', 'asc')
                    ->get();
                $item = ArrayHelper::betweenDates($record, $unique->ip, $settings);
                if(!empty($item)) {
                    $array[] = $item;
                } 
            }
            return ArrayHelper::sortByCount($array);
        });
        return $plunk;
    }

    public function getItem($request)
    {
        $settings = self::getSettings($request->type);
        $name = self::generateName($request, $settings, $request->type);
        $array = Cache::store('redis')->get($name);
        $key = array_search($request->ip, array_column($array, 'ip'));
        return $array[$key];
    }

    public function getGroupItem($request) 
    {
        $newArray = [];
        $array = self::getItem($request);
        foreach($array['array'] as $key => $item) {
            if(!array_key_exists($item['user'], $newArray)) {
                $newArray[$item['user']] = 0;
            }
            $newArray[$item['user']] += 1;
        }
        return $newArray;
    }

    public function getReplication($request)
    {
        Cache::store('redis')->flush();
        $periodList = ['today', 'yesterday', 'week', 'month', 'quarter', 'year'];
        foreach($periodList as $period) {
            $request->period = $period;
            $plunk = self::getPlunk($request, 'plunk');
            $breaking = self::getBreaking($request, 'breaking');
        }
        $array = array_merge($plunk, $breaking);
        foreach($array as $item) {
            Suspicious::updateOrCreate(
                ['ip' => $item['ip']], 
                [
                    'ip'    => $item['ip'],
                    'place' => $item['place'],
                    'check' => 1,
                    'type'  => $item['type']
                ]
            );
        }
    }

    public function getReports($request) 
    {
        $name = 'reports:'.$request->main_search.$request->ip_search.$request->date_search;
        $reports = Cache::store('redis')->remember($name, $this->cache_expiration, function() use ($request) {
            $students = Student::whereRaw("
                concat(StudentLastName, ' ', StudentFirstName, ' ', StudentSecondName) like '%" . $request->main_search . "%'
            ")->orWhere('StudentLogin', $request->main_search)->get();
            foreach($students as $student) {
                $statistics = Statistic::where(function($query) use ($request, $student) {
                    $query->whereNotIn('ip', $this->ip_verified);
                    $query->where([['user', $student->StudentLogin], ['action', 'login']]);
                    $request->ip_search ? $query->where('ip', $request->ip_search) : false;
                    $request->date_search ? $query->whereDate('date', $request->date_search) : false;
                })->get();
                $data = ArrayHelper::reportsList($statistics);
            }
            return $data;
        });
        return $reports;
    }
}