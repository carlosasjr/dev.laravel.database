<?php

namespace App\Http\Controllers;

use App\Models\evaluation;
use App\Models\Product;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class ProductController extends Controller
{
    public function index()
    {
        //RELACIONAMENTO ONE TO MANY
        //UM PARA MUITOS

        //localiza um produto
        $product = Product::where('flag', '=', 'produto-exemplo')->first();

        //retorna uma colletion com todos os registros da tabela evaluations vinculado ao produto
        $evaluations = $product->evaluations()->get();
        //dd($evaluations);


        //retorna as evaluations com stats = 5 do produto
        $evaluations5 = $product->evaluations()->where('stars', '=', '5')->get();
        //dd($evaluations5);


        //otimizando a pesquisa para trazer os dados dos produtos e evaluations na mesma consulta sql
        $produto = Product::with('evaluations')->where('flag', '=', 'produto-exemplo')
                                                        ->first();
        $evaluations = $produto->evaluations;

        foreach ($evaluations as $ev) {
            echo $ev->testimony . '<br>';
        }


        echo '<hr>';

        //para trazer todos os produtos com suas respectivas avaliações
        $produtos = Product::with('evaluations')->get();

        foreach ($produtos as $produto) {
            echo $produto->name . '<br>';
            echo '<ul>';
            foreach ($produto->evaluations as $evaluation) {
                echo '<li>' . $evaluation->testimony . '</li>';
            }
            echo '</ul>';
        }

        echo '<hr>';

        //com o relacionamento com o usuário também é possível pegar os dados do usuário que
        //fez a evaluation
        //Testa forma, é realizada duas consultas
        //A Primeira em produtos e evaluations
        //A Segunda em usuários: $evaluation->user
        $produtos = Product::with('evaluations')->get();

        foreach ($produtos as $produto) {
            echo $produto->name . '<br>';
            echo '<ul>';
            foreach ($produto->evaluations as $evaluation) {
                echo '<li> O usuário:' . $evaluation->user->name . ' avaliou em ' . $evaluation->stars .
                    ' estrelas, depoimento: ' . $evaluation->testimony . '</li>';
            }
            echo '</ul>';
        }

        echo '<hr>';

        //Para otimizar esta pesquisa vamos acrescentar o user no with
        //desta forma, só é realizada uma unica consulta ao banco
        //evaluations.user
        $produtos = Product::with('evaluations.user')->get();

        foreach ($produtos as $produto) {
            echo $produto->name . '<br>';
            echo '<ul>';
            foreach ($produto->evaluations as $evaluation) {
                echo '<li> O usuário:' . $evaluation->user->name . ' avaliou em ' . $evaluation->stars .
                    ' estrelas, depoimento: ' . $evaluation->testimony . '</li>';
            }
            echo '</ul>';
        }


        //para retornar apenas algumas colunas
        //obrigado a incluir o id
        //não dar espaço entre as colunas
        $products = Product::with('evaluations:id,stars,testimony')->get();
       // dd($product);



        //CONSTRAINING EAGER LOADS
        //listar os produtos com avaliações visiveis
        $products = Product::with(['evaluations' => function($query) {
            $query->where('visible', true);
        }])->get();

        foreach ($products as $produto) {
            echo $produto->name . '<br>';
            echo '<ul>';
            foreach ($produto->evaluations as $evaluation) {
                echo '<li> O usuário:' . $evaluation->user->name . ' avaliou em ' . $evaluation->stars .
                    ' estrelas, depoimento: ' . $evaluation->testimony . '</li>';
            }
            echo '</ul>';
        }



        //listar os produtos com as avaliações em ordem de data
        $products = Product::with(['evaluations' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])->get();

        foreach ($products as $produto) {
            echo $produto->name . '<br>';
            echo '<ul>';
            foreach ($produto->evaluations as $evaluation) {
                echo '<li> O usuário:' . $evaluation->user->name . ' avaliou em ' . $evaluation->stars .
                    ' estrelas, depoimento: ' . $evaluation->testimony . '</li>';
            }
            echo '</ul>';
        }


        //listar o produto id = 1 com as avaliações em ordem de data e visiveis
        $products = Product::with(['evaluations' => function($query) {
            $query->where('visible', true)
                  ->orderBy('created_at', 'desc');
        }])->where('id', 1)->get();

        foreach ($products as $produto) {
            echo $produto->name . '<br>';
            echo '<ul>';
            foreach ($produto->evaluations as $evaluation) {
                echo '<li> O usuário:' . $evaluation->user->name . ' avaliou em ' . $evaluation->stars .
                    ' estrelas, depoimento: ' . $evaluation->testimony . '</li>';
            }
            echo '</ul>';
        }



    }

    public function show($id)
    {
        if ($id) {
            $produto = Product::with('evaluations')
                ->where('id', $id)
                ->first();

            echo $produto->name . '<br>';
            echo '<ul>';
            foreach ($produto->evaluations as $evaluation) {
                echo '<li>' . $evaluation->testimony . '</li>';
            }
            echo '</ul>';
        }

        //imprimindo no controller apenas para teste
        //correto chamar a view
    }

    public function store()
    {
        $evaluation = new evaluation(
            [
                'stars' => 5,
                'testimony' => 'Muito bom'
            ]
        );

        //Primeira forma de salvar um item no produto.
        //localiza o produto
        $product = Product::find(1);

        //vincular
        $product->evaluations()->save($evaluation);


        //inserindo varios itens no produto 1
        //usando saveMany
        $product = Product::find(1);

        $product->evaluations()->saveMany([
            new evaluation([
                'stars' => 3,
                'testimony' => 'Muito bom'
            ]),

            new evaluation([
                'stars' => '2',
                'testimony' => 'bom'
            ])
        ]);

        //inserindo um itens no produto 2
        //usando create (recomendado)
        $product = Product::find(2);
        $evaluation = $product->evaluations()->create([
            'stars' => 5,
            'testimony' => 'Muito bom'
        ]);

        //inserindo varios itens no produto 2
        //usando createMany (recomendado)
        $product = Product::find(2);
        $product->evaluations()->createMany([
            [
                'stars' => '5',
                'testimony' => 'Muito bom'
            ],

            [
                'stars' => '1',
                'testimony' => 'Ruim'
            ]
        ]);

    }

    public function update(Request $request, $id)
    {
        //alterar diretamente o item
        $evaluation = evaluation::find($id);
        $evaluation->update([
            'stars' => '0',
            'testimony' => 'pessimo'
        ]);

        //alterar recebendo dados do formulário
        if ($request) {
            $evaluation = evaluation::find($id);
            $evaluation->update($request->only('stars', 'testimony'));
        }

    }

    public function item($id)
    {
        //busca o item pelo id
        $evaluation = evaluation::find($id);

        //recupera o produto deste item
        if ($evaluation) {
            $product = $evaluation->product;
            //dd($product);
        }


        //pegar o produto do item realizando apenas uma consulta ao banco
        //usando with
        $evaluation = evaluation::with('product')->find($id);

        if ($evaluation) {

           $product = $evaluation->product;
           dd($evaluation->testimony, $product);
        }
    }
}
