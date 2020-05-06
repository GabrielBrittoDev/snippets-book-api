<?php

namespace App\Http\Controllers\api\email;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class VerificationController extends Controller
{
    /*
|--------------------------------------------------------------------------
| Email Verification Controller
|--------------------------------------------------------------------------
|
| This controller is responsible for handling email verification for any
| user that recently registered with the application. Emails may also
| be re-sent if the user didn't receive the original email message.
|
*/


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('apiJwt')->only('resend');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function resend(Request $request){
        if ($request->user()->hasVerifiedEmail()){
            return response()->json(['msg' => 'Já verificado']);
        }
        $request->user()->sendEmailVerificationNotification();

        if ($request->wantsJson()){
            return response()->json(['msg' => 'Email enviado']);
        }

        return back()->with('resent', true);


    }

    public function verify(Request $request){
        $user = User::find($request->route('id'));
        JWTAuth::fromUser($user);

        if($request->route('id') != $user->id){
            throw new AuthorizationException;
        }

        if ($user->hasVerifiedEmail()){
            return response()->json(['msg' => 'Já verificado']);
        }

        if ($user->markEmailAsVerified()){
            event(new Verified($request->user()));
        }

        return response()->json(['msg' => 'Email verificado com sucesso']);
    }



}
