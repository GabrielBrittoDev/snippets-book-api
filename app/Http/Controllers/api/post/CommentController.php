<?php

namespace App\Http\Controllers\api\post;

    use App\API\ApiError;
    use App\Comment;
    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Cache;

    class CommentController extends Controller
{

    private $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
        $this->middleware('apiJwt')->except('show');
    }

    public function store(Request $request, int $postId)
    {
        try {
            $validatedComment = $request->validate([
                'description' => 'min:1|max:230|required',
                'user_id' => 'required',
            ]);

            $validatedComment['post_id'] = $postId;
            $this->comment->create($validatedComment);

            return response()->json(['msg' => 'Comentario feito com sucesso'], 200);
        } catch (\Exception $e) {
            return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
        }
    }

    public function update(Request $request, int $postId, $commentId)
    {
        try {
            $comment = $this->comment->findOrFail($commentId);

            $validatedComment = $request->validate([
                'description' => 'min:1|max:230|required',
                'user_id' => 'required',
            ]);
            $validatedComment['post_id'] = $postId;

            $this->authorize('update', $comment);

            $comment->update($validatedComment);
            return response()->json(['msg' => 'Comentario ataualizado com sucesso!'], 200);
        } catch (\Exception $e) {
            return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
        }
    }

    public function destroy(int $postId, int $commentId)
    {
        try {
            $comment = $this->comment->findOrFail($commentId);

            $this->authorize('update', $comment);

            $comment->delete();

            return response()->json(['msg' => 'Comentario deletado com sucesso!'], 200);
        } catch (\Exception $e) {
            return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
        }
    }

    public function show(int $idPost){
        try {
            $comments = $this->comment::where('post_id', $idPost)->get();
            foreach ($comments as $value){
                $value['likeCount'] = $value->likes()->count();
            }
            return response()->json(compact('comments'), 200);
        } catch (\Exception $e) {
            return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
        }
    }

    public function like(int $postId, int $commentId)
    {
        try {
            $comment = $this->comment->findOrFail($commentId);
            $state = $comment->likes()->toggle(auth()->user()->id);
            $stateMsg = $state['attached'] ? 'curtido' : 'descurtido';

            return response()->json(['msg' => 'Comentario '. $stateMsg . ' com sucesso']);
        } catch (\Exception $e) {
            return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
        }
    }
}
