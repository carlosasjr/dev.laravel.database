<h1>Order: {{ $order->identify }}</h1>

@foreach($order->products as $product)
    <p>Nome: {{ $product->name  }}</p>
    <p>Preço: {{ $product->pivot->price  }}</p>
    <p>Qtd: {{ $product->pivot->qtd  }}</p>
    <p>Total: {{ $product->pivot->price * $product->pivot->qtd  }}</p>
    <hr>
@endforeach


