<?php

namespace App;

use App\Mail\UserWelcomeMail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','username'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    protected static function boot()
    {
        parent::boot();

        static::created(function ($user){
            $user->profile()->create([
                'phrase' => $user->username.' coloca uma frase maneira ai!',
            ]);
            Mail::to($user->email)->send(new UserWelcomeMail($user->username));
        });

    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }


    public function following(){
        return $this->belongsToMany(Profile::class);
    }

    public function skills(){
        return $this->belongsToMany(Skill::class, 'skill_user');
    }

    public function profile(){
        return $this->hasOne(Profile::class);
    }
}
