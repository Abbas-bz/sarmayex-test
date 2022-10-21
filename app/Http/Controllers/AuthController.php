<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Task;
use App\Services\PassportLoginService;


class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        $loginservice = new PassportLoginService();
        $token = $loginservice->login($request->all());

        return response()->json([
            'message' => 'Login was successful',
            'data' => $token
        ]);
    }

    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        $loginservice = new PassportLoginService();
        $result = $loginservice->logout();

        return response()->json([
            'message' => 'Logout was successful',
            'data' => null
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterRequest $request)
    {
        $loginservice = new PassportLoginService();
        $result = $loginservice->register($request->all());

        return response()->json([
            'message' => 'Registered successfully',
            'data' => $result
        ]);
    }
}
