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


    }


}
