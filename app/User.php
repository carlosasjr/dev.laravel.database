<?php

namespace App;

use App\Models\Order;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
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

    /**
     * Retorna um objeto Location que pertence ao User
     * Relacionamento One to One
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function location()
    {
        return $this->hasOne(Location::class,'user_id', 'id');
    }


    /**
     * Relacionamento One to Many
     * Retorna todas as ordens deste user.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
       return $this->hasMany(Order::class);

    }
}
