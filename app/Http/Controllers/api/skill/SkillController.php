<?php

namespace App\Http\Controllers\api\skill;

use App\API\ApiError;
use App\Http\Controllers\Controller;
use App\Skill;
use App\User;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    private $skill;

    public function __construct(Skill $skill){
        $this->skill = $skill;
    }

    public function index(){
        try{
            $skills = $this->skill->all();
            return response()->json(compact('skills'), 200);
        } catch(\Exception $e){
            return response()->json(ApiError::errorMessage('Falha ao obter skills', 1010), 500);
        }
    }

}

