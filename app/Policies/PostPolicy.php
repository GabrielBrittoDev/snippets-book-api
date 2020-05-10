<?php

namespace App\Policies;

use App\Post;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function destroy(User $user,Post $post)
    {
        return $user->id === $post->user_id
            ? Response::allow()
            : Response::deny('Você não pode deletar esse post.');
    }

    public function show(User $user,Post $post){

        return $user->id === $post->user_id
            ? Response::allow()
            : Response::deny('Esse post é restrito');

    }

    public function update(User $user, Post $post){
        return $user->id === $post->user_id
            ? Response::allow()
            : Response::deny('Você não pode atualizar esse post.');
    }
}
