<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{    
    public function index()
    {
        $roles = Role::latest('id')->get();
        return response()->json([
            'roles' => $roles
        ], 200);
    }
}
