<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id', 'identify', 'code', 'status', 'payment_method', 'date'];

    /**
     * Relacionamento Many to Many
     * Retorna todos os produtos da tabela sales para esta order
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'sales')
                    ->withPivot('id', 'qtd', 'price')
                    ->withTimestamps();
    }

}
