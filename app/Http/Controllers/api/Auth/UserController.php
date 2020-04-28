<?php

namespace App\Http\Controllers\api\Auth;

use App\API\ApiError;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserCreateRequest;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }


    public function create(UserCreateRequest $request){
        try {

            $this->middleware('guest');

            $userData = $request->validated();

            $userData['password'] = bcrypt($request['password']);

            $this->user->create($userData);

            $response = ['data' => ['msg' => 'User criado com sucesso!']];

            return response()->json($response, 201);

        } catch (\Exception $e){
            if (config('app.debug')){
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
            }
            return response()->json(ApiError::errorMessage('Houve um erro ao realizar a operação de cadastrar', 1010), 500);

        }
    }
}
