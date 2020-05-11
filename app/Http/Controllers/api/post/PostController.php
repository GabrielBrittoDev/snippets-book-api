<?php

namespace App\Http\Controllers\api\post;

use App\API\ApiError;
use App\Http\Controllers\Controller;
use App\Http\Requests\post\PostPutRequest;
use App\Http\Requests\post\PostStoreRequest;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{

    private $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
        $this->middleware('apiJwt')->except('show');
    }

    public function store(PostStoreRequest $request){
        try {
            $post = $request->validated();

            $post['user_id'] = auth()->user()->id;
            $this->post->create($post);
            $response = ['msg' => 'Postado com sucesso'];

            return response()->json($response, 200);

        } catch (\Exception $e){
            return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
        }
    }

    public function show(int $id){
        try {
             $post = $this->post->findOrFail($id)->with('comments')->get()->first();
            if ($post->restrict) {
                $this->authorize('show', $post);
            }

            $likesCount = Cache::remember(
                'count.likes'. $post->id,
                now()->addSeconds(30),
                function () use ($post) {
                    return $post->likes()->count();
                });

            return response()->json(compact('post', 'likesCount'), 200);
        } catch (\Exception $e){
            return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
        }
    }


    public function update(PostPutRequest $request, int $id){
        try {
            $post = $this->post->findOrFail($id);
            $post['user_id'] = auth()->user()->id;

            $post->update($request->validated());

            return response()->json(['msg' => 'Post atualizado com sucesso']);
        } catch(\Exception $e){
            return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
        }
    }

    public function destroy(int $id){
        try {
            $post = $this->post->findOrFail($id);
            $this->authorize('destroy', $post);

            $post->delete();
            return response()->json(['msg' => 'Post deletado com sucesso']);
        } catch(\Exception $e){
            return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
        }
    }


    public function like(int $id){
        try {
            $post = $this->post->findOrFail($id);
            $state = $post->likes()->toggle(auth()->user()->id);
            $stateMsg = $state['attached'] ? 'curtido' : 'descurtido';
            return response()->json(['msg' => 'Post '. $stateMsg .' com sucesso']);
        } catch (\Exception $e){
            return response()->json(ApiError::errorMessage($e->getMessage(), 1010),500);
        }
    }
}
