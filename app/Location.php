<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Location extends Model
{
    protected $table = 'locations';

    protected $fillable = ['latitude', 'longitude'];

    //Recuperar os dados do usuário desta localização
    //Relacionamento inverto de One To One
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
