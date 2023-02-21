<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Settings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
    public function __construct() 
    {
        $this->middleware('auth:api');
    }

    public function index() 
    {
        $settings = Settings::all();
        return response()->json([
            'settings' => $settings
        ]);
    }

    public function save(Request $request) 
    {
        $settings = json_decode($request->array);
        foreach($settings as $item) {
            $created = Settings::updateOrCreate(
                [
                    'user_id' => auth()->user()->id, 
                    'type' => $item->type
                ],
                [
                    'user_id'   => auth()->user()->id,
                    'type'      => $item->type,
                    'status'    => $item->status,
                    'amount'    => @$item->amount ?: null,
                    'action'    => @$item->action ?: null,
                    'metering'  => @$item->metering ?: null
                ]
            );
        }
        if($created) {
            return response()->json([
                'message'  => 'Настройки успешно сохранены!', 
                'verified' => $created
            ], 200);
        } 
        return response()->json([
            'message' => 'Произошла ошибка!'
        ], 500);
    }
}
