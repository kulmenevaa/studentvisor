<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Verified;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\VerifiedRequest;
use App\Http\Resources\VerifiedResource;

class VerifiedController extends Controller
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
        $verified = Verified::orderBy('created_at', 'desc')->paginate(10);
        return response()->json([
            'verified' => $verified
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VerifiedRequest $request)
    {
        $created = Verified::create($request->all());
        if($created) {
            return response()->json([
                'message'  => 'IP успешно добавлен!', 
                'verified' => new VerifiedResource($created)
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
    public function update(VerifiedRequest $request, $id)
    {
        $data = $request->all();
        $data['updated_at'] = date('d.m.Y H:i:s');
        $verified = Verified::find($id);
        $updated = $verified->update($data);
        if($updated) {
            return response()->json([
                'message'  => 'IP успешно изменен!', 
                'verified' => new VerifiedResource($verified)
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
        $verified = Verified::find($id);
        if($verified->delete()) {
            return response()->json([
                'message' => 'IP успешно удален!'
            ], 200);
        }
        return response()->json([
            'message' => 'Произошла ошибка!'
        ], 500);
    }
}
