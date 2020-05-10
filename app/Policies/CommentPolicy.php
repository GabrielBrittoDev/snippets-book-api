<?php

namespace App\Policies;

use App\Comment;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class CommentPolicy
{
    use HandlesAuthorization;

    public function destroy(User $user, Comment $comment){
               return $user->id === $comment->user_id &&
               $user->id === $comment->post()->user()->id
                   ? Response::allow()
                   : Response::deny('Você não pode deletar esse comentário');
    }


    public function update(User $user,Comment $comment){
        return $user->id === $comment->user_id
            ? Response::allow()
            : Response::deny('Você não pode atualizar esse comentário');
    }
}
