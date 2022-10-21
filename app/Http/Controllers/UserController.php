<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('users.index');

        $users = User::query()->paginate($request->get('limit', 20));

        return response()->json([
            'message' => 'Users list',
            'data' => UserResource::collection($users)
        ]);
    }
}
