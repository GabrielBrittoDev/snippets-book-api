<?php

namespace App\Http\Controllers\api\skill;

use App\API\ApiError;
use App\Http\Controllers\Controller;
use App\Skill;
use App\User;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function __construct(){
        $this->middleware('apiJwt');
    }

    public function index(){
        try{
            return response()->json(['data' => [Skill::all()]], 200);
        } catch(\Exception $e){
            return response()->json(ApiError::errorMessage('Falaha ao pegar skills', 0), 500);
        }
    }

    public function store(Skill $id){
        try{
            auth()->user()->skills()->toggle($id);
            return response()->json(['data' => ['msg' => $id->name . ' alterado com sucesso']], 200);
        } catch (\Exception $e){
            return response()->json(['data' => [$e]]);
        }
    }
}
