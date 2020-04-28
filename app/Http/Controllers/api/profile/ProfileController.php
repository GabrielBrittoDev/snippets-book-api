<?php

namespace App\Http\Controllers\api\profile;

use App\API\ApiError;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Profile;
use App\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    private $profile;

    public function __construct(Profile $profile){
        $this->profile = $profile;
    }

    public function show(int $id){
        $data = User::findOrFail($id);

        return response()->json($data->only('name', 'username', 'profile', 'skills'), 200);
    }

    public function update(ProfileUpdateRequest $request, int $id){
        try {
        $profile = $this->profile->find($id);

        $this->authorize('update', $profile);

        $profileData = $request->validated();

        $profile->update($profileData);

        $response = ['msg'=> 'Profile atualizada com sucesso!'];
            return response()->json($response, 201);
        } catch (\Exception $e){
            return response()->json(ApiError::errorMessage($e->getMessage(), 10), 500);
        }

    }

    public function follow(Profile $id){
        try {
            auth()->user()->profile->followers()->toggle($id);
            return response()->json('Success', 200);
        } catch (\Exception $e){
            return response()->json(ApiError::errorMessage($e->getMessage(), 0), 500);
        }
    }

}
