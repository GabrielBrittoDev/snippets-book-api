<?php

namespace App\Http\Controllers\api\user;

use App\API\ApiError;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
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


    public function search(Request $request){
        try {
            $query = $request->input('query');
            $users = User::where('username', 'like', "%$query%")->with('profile')->get(['id','username', 'name']);

            return response()->json($users, 200);
        } catch (\Exception $e){
            return response()->json(['error' => ApiError::errorMessage($e->getMessage(), 500)], 500);
        }

    }

    public function update(UserUpdateRequest $request, int $id){
        try {
            $user = $this->user->findOrFail($id);

            $this->authorize('update', $user);
            $validatedData = $request->validated();
            $validatedData['password'] = bcrypt($request['password']);

            $user->update($validatedData);

            return response()->json(['msg' => 'User updated with success'], 200);
        } catch (\Exception $e){
            return response()->json(['error' => ApiError::errorMessage($e->getMessage(), 500)], 500);
        }
    }
}
