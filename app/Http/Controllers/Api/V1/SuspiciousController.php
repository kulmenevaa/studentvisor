<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Suspicious;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SuspiciousRequest;
use App\Http\Resources\SuspiciousResource;
use Stevebauman\Location\Facades\Location;

class SuspiciousController extends Controller
{
    public function __construct() 
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $suspicious = Suspicious::orderBy('created_at', 'desc')->paginate(10); 
        return response()->json([
            'suspicious' => $suspicious
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SuspiciousRequest $request)
    {
        $data = $request->all();
        $data['type'] = 'default';
        $location = Location::get($data['ip']); 
        $data['place'] = $location ? $location->countryName.', '.$location->cityName : null;
        $created = Suspicious::create($data);
        if($created) {
            return response()->json([
                'message'  => 'IP успешно добавлен!', 
                'suspicious' => new SuspiciousResource($created)
            ], 200);
        } 
        return response()->json([
            'message' => 'Произошла ошибка!'
        ], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json([
            'message' => 'Страница не найдена!'
        ], 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SuspiciousRequest $request, $id)
    {
        $data = $request->all();
        $data['updated_at'] = date('d.m.Y H:i:s');
        $suspicious = Suspicious::find($id);
        $updated = $suspicious->update($data);
        if($updated) {
            return response()->json([
                'message'  => 'IP успешно изменен!', 
                'suspicious' => new SuspiciousResource($suspicious)
            ], 200);
        }
        return response()->json([
            'message' => 'Произошла ошибка!'
        ], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $suspicious = Suspicious::find($id);
        if($suspicious->delete()) {
            return response()->json([
                'message' => 'IP успешно удален!'
            ], 200);
        }
        return response()->json([
            'message' => 'Произошла ошибка!'
        ], 500);
    }
}
