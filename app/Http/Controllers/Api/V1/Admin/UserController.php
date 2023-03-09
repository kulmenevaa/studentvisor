<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->with('role')->paginate(10); ;
        return response()->json([
            'users' => $users
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        $created = User::create($data);
        if($created) {
            return response()->json([
                'message'  => 'Пользователь успешно добавлен!', 
                'users' => new UserResource($created)
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
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        $data['updated_at'] = date('d.m.Y H:i:s');
        $updated = $user->update($data);
        if($updated) {
            return response()->json([
                'message'  => 'Пользователь успешно изменен!', 
                'users' => new UserResource($user)
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
    public function destroy(User $user)
    {
        if($user->delete()) {
            return response()->json([
                'message' => 'Пользователь успешно удален!'
            ], 200);
        }
        return response()->json([
            'message' => 'Произошла ошибка!'
        ], 500);
    }
}
