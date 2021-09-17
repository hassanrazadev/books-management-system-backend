<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller {

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse {
        if (auth()->attempt($request->validated())) {
            $user = auth()->user();
            $this->setMessage('Login success!');
            $this->setData([
                'user' => $user,
                'access_token' => $this->getAccessToken($user)
            ]);
        } else {
            $this->setMessage('Email or password is incorrect!');
            $this->setStatus(false);
            $this->setStatusCode(400);
        }
        return $this->getApiResponse();
    }

    /**
     * @param $user
     * @return mixed
     */
    protected function getAccessToken($user) {
        return $user->createToken(config('app.name'))->accessToken;
    }
}
