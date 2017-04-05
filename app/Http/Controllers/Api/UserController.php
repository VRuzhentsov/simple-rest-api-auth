<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\UpdateUserRequest;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{

    /**
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll(){
        return User::all();
    }

    /**
     * Update current user model
     *
     * @param UpdateUserRequest $request
     *
     * @return UserController|JsonResponse
     */
    public function updateCurrent(UpdateUserRequest $request){
        $user = \Auth::user();
        $user->update($request->all());
        return JsonResponse::create([$user]);
    }

    /**
     * Show user
     * @param User $user
     *
     * @return User
     */
    public function show(User $user){
        return $user;
    }
}
