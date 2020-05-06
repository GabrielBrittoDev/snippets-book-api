<?php

namespace App\Http\Controllers\api\Auth;

use App\API\ApiError;
use App\Http\Controllers\Controller;
use App\Mail\UserResetPasswordMail;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{

    public function __construct(){
        $this->middleware('guest');
    }

    public function reset(Request $request){
        try {
            if (!$user = User::where('email', $request->email)->first()){
                return response()->json(['error' => 'Email não encontrado'], 404);
            }

            $newPassword = str_random(8);
            $user->password = bcrypt($newPassword);
            $user->save();

            Mail::to($request->email)->send(new UserResetPasswordMail($newPassword));
            return response()->json(['msg' => 'Email enviado com sucesso'], 200);
        } catch( \Exception $e){
            return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
        }
    }


}
