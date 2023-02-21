<?php

namespace App\Http\Controllers\Api\V1;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Actions\StatisticAction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class StatisticController extends Controller
{
    public function __construct() 
    {
        $this->action = new StatisticAction();
    }

    public function breaking(Request $request) 
    {
        $type = 'breaking';
        $breaking = $this->action->getBreaking($request, $type);
        return response()->json([
            'array' => $breaking, 
            'count' => count($breaking),
            'type'  => $type
        ], 200);
    }

    public function plunk(Request $request)
    {
        $type = 'plunk';
        $plunk = $this->action->getPlunk($request, $type);
        return response()->json([
            'array' => $plunk, 
            'count' => count($plunk),
            'type'  => $type
        ], 200);
    }

    public function reports(Request $request) 
    {
        /*
        $validator = Validator::make($request->all(), ['main_search' => 'required']);
        if($validator->fails()) {
            return response()->json([
                'errors' => $validator->messages()
            ], 422);
        }
        */
        $reports = $this->action->getReports($request, 'reports');
        return response()->json([
            'statistics' => $reports
        ], 200);
    }

    public function item(Request $request)
    {
        $item = $this->action->getItem($request);
        return response()->json($item, 200);
    }

    public function groupItem(Request $request)
    {
        $array = $this->action->getGroupItem($request);
        return response()->json([
            'array' => $array,
            'count' => count($array)
        ], 200);
    }
    
    public function replication(Request $request) {
        $item = $this->action->getReplication($request);
    }
}
