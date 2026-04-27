<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserResource;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Return the authenticated user's profile.
     */
    public function profile(Request $request): UserResource
    {
        return new UserResource($request->user());
    }
}
