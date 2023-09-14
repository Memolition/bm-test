<?php

namespace App\Http\Controllers;

use App\Declarations\ApiError;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Models\Users;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();

        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->only(['username', 'password', 'name']);

        try {
            return User::create([
                'name' => $data['name'],
                'username' => $data['username'],
                'password' => Hash::make($data['password']),
                'apiToken' => Str::random(60),
            ]);
        } catch (UniqueConstraintViolationException $e) {
            return response(ApiError::invalidValueDuplicated('Username'), 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $entity = User::find($id);

        if(!$entity) {
            return response(ApiError::entityNotFound('User', $id), 400);
        }

        return response()->json($entity);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $users)
    {
        //
    }

    /**
     * Authenticates an user and returns a token to be used with the API
     */
    public function login(LoginRequest $request) {
        $data = $request->only('username', 'password');
        $user = User::where('username', $data['username'])->get();

        if(count($user) <= 0) {
            return response(ApiError::entityNotFound('User', $data['username']), 400);
        }

        $validUser = $user->first();
        $isValid = Hash::check($data['password'], $validUser->getAuthPassword());

        if(!$isValid) {
            return response(ApiError::invalidPassword($data['username']), 400);
        }

        return response()->json($validUser->createToken('auth'));
    }

    /**
     * Logout
     * Deletes all active tokens for a specific user
     */
    public function logout($id) {
        $user = User::find($id);

        if(!$user) {
            return response(ApiError::entityNotFound('User', $id), 400);
        }
        
        $user->tokens()->delete();

        return response(null, 204);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $entity = User::find($id);

        if(empty($entity)) {
            return response(ApiError::entityNotFound('User', $id), 400);
        }

        $entity->delete();

        return response(null, 204);
    }
}
