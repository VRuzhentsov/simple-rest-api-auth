<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\UpdateUserRequest;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function updateCurrent(UpdateUserRequest $request){
        $user = \Auth::user();
        $user->update($request->all());
        return JsonResponse::create([$user]);
    }

    public function show(User $user){
        return $user;
    }
}
