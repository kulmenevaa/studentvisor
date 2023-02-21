<?php

namespace App\Helpers;

use App\Models\Student;
use App\Models\Suspicious;
use App\Helpers\LocationHelper;
use Stevebauman\Location\Facades\Location;

class ArrayHelper
{
    public function verifiedList($ip_verified) 
    {
        $result = [];
        foreach($ip_verified as $ip) {
            if($ips = stristr($ip->ip, '*', true)) {
                $result[] = $ips;
            }
        }
        if(count($result) > 0) {
            $sql = 'SUBSTRING(ip, 1, 6) NOT LIKE ';
            return "$sql " . implode(" AND $sql ", $result);
        }
        return false;
    }

    public function reportsList($array)
    {
        $data = [];
        $ip_suspicious = Suspicious::where('check', 1)->select('ip')->get();
        foreach($array as $item) {
            $student = Student::where('StudentLogin', $item->user)->first();
            $data[] = [
                'fio'           => $student ? $student->getFio() : null,
                'user'          => $item->user,
                'date'          => $item->date,
                'ip'            => $item->ip,
                'place'         => LocationHelper::place($item->ip),
                'suspicious'    => $ip_suspicious->contains('ip', $item->ip) ? 1 : 0,
            ];
        }
        return $data;
    }

    public function betweenDates($record, $ip, $settings)
    {
        $data = [];
        $array = [];
        $result = [];
        $action = $settings->action;
        $metering = $settings->metering;
        for($i = 0; $i < count($record); $i++) {
            $item = $record[$i];
            for($j = 0; $j < count($record); $j++) {
                $newDate = date('Y-m-d H:i:s', strtotime("+ $action $metering", strtotime($record[$j]->date)));
                $prevDate = date('Y-m-d H:i:s', strtotime("- $action $metering", strtotime($record[$j]->date)));
                if($prevDate <= $item->date && $item->date <= $newDate && $item != $record[$j]) {
                    array_push($data, $item);
                }
            }
            if(in_array($item, $data)) {
                $array[] = [
                    'ip'        => $item->ip,
                    'date'      => $item->date,
                    'user'      => $item->user,
                    'category'  => $item->category,
                    'info'      => $item->info
                ];
            }
        }
        if(count($array) >= $settings->amount) {
            $result = [
                'ip'    => $ip,         
                'place' => LocationHelper::place($ip), 
                'count' => count($array), // количество авторизаций
                'array' => $array,
                'type'  => $settings->type
            ];
        }
        return $result;
    }

    public function sortByCount($array) 
    {
        $column = array_column($array, 'count');
        array_multisort($column, SORT_DESC, $array);
        return $array;
    }
}