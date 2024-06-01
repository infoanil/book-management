<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Traits\CandidateApiTrait;

class AuthController extends Controller
{
    use CandidateApiTrait;

    public function login(LoginRequest $request)
    {
        $validated = $request->validate([
            'email' => 'required | email',
            'password' => 'required',
        ]);
        $response = $this->candidateLogin($validated['email'], $validated['password']);

        if ($response->status() === 200) {
            $token = $response->object()->token_key;
            $user = $response->object()->user;

            return $this->sendResponse([
                'token' => $token,
                'user' => $user,
            ]);
        }

        return $this->sendErrorResponse(@$response->object()->exception ?? 'Something went wrong');
    }
}
