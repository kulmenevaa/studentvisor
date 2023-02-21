<?php

namespace App\Helpers;

use Carbon\Carbon;

class PeriodHelper
{
    public function set($period) 
    {
        switch ($period) {
            case 'year':
                return [
                    Carbon::now()->startOfYear(), 
                    Carbon::now()->endOfYear()
                ];
                break;
            case 'quarter':
                return [
                    Carbon::now()->startOfQuarter(), 
                    Carbon::now()->endOfQuarter()
                ];
                break;
            case 'month':
                return [
                    Carbon::now()->startOfMonth(), 
                    Carbon::now()->endOfMonth()
                ];
                break;
            case 'week':
                return [
                    Carbon::now()->startOfWeek(), 
                    Carbon::now()->endOfWeek()
                ];
                break;
            case 'yesterday':
                return [
                    Carbon::now()::yesterday()->toDateTimeString(), 
                    Carbon::now()::today()->toDateTimeString()
                ];
                break;
            default: 
                return [
                    Carbon::now()->startOfDay(), 
                    Carbon::now()->endOfDay()
                ];
                break;
        }
    }
}