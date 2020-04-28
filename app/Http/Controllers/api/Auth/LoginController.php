<?php

namespace App\Http\Controllers\api\Auth;

use App\API\ApiError;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    private $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function __invoke(Request $request)
    {
        if (!$token = auth()->attempt($request->only('email', 'password'))){
            return response(null, 401);
        }

        $token = $this->user->getKey();
        return response()->json(compact('token'));
    }
}
