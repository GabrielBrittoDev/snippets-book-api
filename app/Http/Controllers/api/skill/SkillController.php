<?php
namespace App\Http\Controllers\api\skill;

use App\API\ApiError;
use App\Http\Controllers\Controller;
use App\Post;
use App\Skill;
use App\User;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    private $skill;

    public function __construct(Skill $skill)
    {
        $this->middleware('apiJwt')->except('index');
        $this->skill = $skill;
    }

    public function index()
    {
        try {
            $skills = $this->skill->all();
            return response()->json(compact('skills'), 200);
        } catch (\Exception $e) {
            return response()->json(ApiError::errorMessage('Falha ao obter skills', 1010), 500);
        }
    }

    public function store(int $id){
        try{
            $state = auth()->user()->skills()->toggle($id);
            $stateMsg = $state['attached'] ? 'adicionado' : 'removido';
            return response()->json(['msg' => 'skill ' . $stateMsg. ' com sucesso'], 200);
        } catch (\Exception $e){
            return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
        }
    }

    public function posts(int $id){
        try {

            $posts = Post::where('skill_id', $id)->with('user')->get();

            $response = ['posts' => $posts ?? '',
                'skill' => $this->skill->findOrFail($id),
                'msg' => 'Foram encontrados ' . $posts->count() . ' post'];

            return response()->json($response, 200);
        } catch (\Exception $e){
            return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
        }
    }

}

