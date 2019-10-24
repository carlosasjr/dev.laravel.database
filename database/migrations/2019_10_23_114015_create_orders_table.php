<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('identify', 191)->unique(); // Identificador unico do pedido
            $table->string('code', 191)->unique(); // Código do pedido: REF123
            $table->enum('status', [1,2,3,4,5,6,7,8,9]); //Status do pagamento
            $table->enum('payment_method', [1,2,3,4,5,6,7]); //Métodos de pagamento
            $table->date('date'); //Data que foi realizado o pedido
            $table->timestamps();

            $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
