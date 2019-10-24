<?php

namespace App\Http\Controllers;

use App\Location;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function createUserLocation()
    {
        $loc = [
            'longitude' => '999',
            'latitude' => '321'
        ];

        //procura o usuário e se encontrar insere uma localização
        $user = User::find('1');
        if ($user) {
            try {
                //o user id foi definido como unico,
                //se usar o comando a baixo mais de uma vez vai dar erro:
                //Integrity constraint violation: 1062 Duplicate entry
                //$user->location()->create($loc);


                //updateOrCreate - Verifica se existe um usuário com o id,
                //se não existir cria
                //e se existir atualiza.
                $user->location()->updateOrCreate(['user_id' => $user->id], $loc);


            } catch (\PDOException $e) {
                dd($e);
            }
        }

        //procura o usuário e a localização em consultas separadas
        $user = User::find('1');

        $localizacao = $user->location;
        // dd($localizacao->longitude, $localizacao->latitude);

        //procurar o usuário e a localização na mesma consulta;
        $user = User::with('location')->find('1');
        //dd($user->name, $user->location->latitude, $user->location->longitude);


        //traz os dados do user a partir do location
        $location = Location::where('latitude', '=', '321')
                            ->where('longitude', '=', '999')
                            ->first();

        $user = $location->user->email;

        dd($user);
    }


    public function store(Request $request)
    {
        //pega todos os dados da request
        $data = $request->all();

        if ($data) {
            $data['password'] = bcrypt($data['password']); //cryptografa a senha

            //cadastra o usuário
            $user = User::created($data);

            //castra a localização do usuário
            $location = $user->location()->updateOrCreate(['user_id' => $user->id], $data);
            //ou
            //$user->location()->create($request->only('latitude', 'longitude'));
        }

    }
}
