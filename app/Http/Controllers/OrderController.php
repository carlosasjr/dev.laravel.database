<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index($id)
    {
        $orderID = $id;
        //Neste exemplo é feito duas consultas ao banco
        //para trazer a ordem, e outra para trazer os produtos, chamando o método products da ordem automaticamente.

        //$order = Order::find($orderID); //Ou Order::where('id', $orderID)->first();


        //otimizando para realizar apenas uma consulta ao banco para trazer a ordem e os produtos da ordem.

        //$order = Order::with('products')->find($orderID);

        //outras formas de trazer os produtos da ordem
        //ideal para filtrar e ordenar os registros

        $order = Order::find($orderID);
        //$products = $order->products()->get();

        //retornar os produtos em ordem alfabética com desc
        //$products = $order->products()->orderBy('name', 'desc')->get();

        //$products = $order->products()->where('products.price', 50)-> orderBy('name')->get();
        $products = $order->products()->where('sales.price', 50)-> orderBy('name')->get();

        return view('order', compact('order', 'products'));
    }

    public function create()
    {
       //recupera o usuário logado, que fez o pedido
        //$user = auth()->user();

        //ou recuperar pelo e-mail
       $user = User::where('email', 'carlos@especializati.com.br')->first();

        //cadastra um novo pedido para o usuário logado
        $order = $user->orders()->create([
           'identify' => uniqid(date('YmdHis')),
           'code' => 'REF' . uniqid(date('YmdHis')),
           'status' => 1,
           'payment_method' => 2,
            'date' => date('Y-m-d')
        ]);

        //relaciona o produto 1 ao pedido
        $order->products()->attach(1, ['qtd' => 1, 'price' => 10.2]);

        $order->products()->attach([
            1 => ['qtd' => 2, 'price' => 10.2],
            2 => ['qtd' => 3, 'price' => 35]
        ]);


        //podemos usar o método sync()
        //ele deleta os que existem e cadastra os novos
        $order = Order::find(5);
        $order->products()->sync([
            1 => ['qtd' => 55, 'price' => 10.2 ],
            2 => ['qtd' => 55, 'price' => 15.2 ]
            ]);


        //sincronizar sem deletar os existem
        //neste caso, mantem os existentes, mas sincroniza pelo id do produto editando os existentes
        $order = Order::find(5);
        $order->products()->syncWithoutDetaching([
            1 => ['qtd' => 99, 'price' => 99 ],
            2 => ['qtd' => 99, 'price' => 99 ]
        ]);


        //outra forma para salvar um único registro
        $order = Order::find(5);
        $product = Product::find(5);
        $order->products()->save($product, ['qtd' => 2, 'price' => 10.55]);


        $order = Order::create([
            'user_id' => $user->id,
            'identify' => uniqid(date('YmdHis')),
            'code' => 'REF' . uniqid(date('YmdHis')),
            'status' => 1,
            'payment_method' => 2,
            'date' => date('Y-m-d')
        ]);

        $order->products()->attach([
            1 => ['qtd' => 2, 'price' => 10.2],
            2 => ['qtd' => 3, 'price' => 35]
        ]);


    }

    public function deletarItem($id, $item)
    {
        //localiza a ordem
        /* $order = Order::find($id);

        //deleta o item da ordem pelo id do produto
        $order->products()->detach($item);

        //para remover mais de um produto
        $order->produts()->detach([1,2,3]);

        //para remover todos os produtos de uma order
        $order->products()->detach();*/
    }

    public function update($id, $item)
    {
        //alterar um item da ordem
        //o item é neste caso, é sempre o id do produto
        $order = Order::find($id);
        $order->products()->updateExistingPivot($item, ['qtd' => 33]);

    }
}
