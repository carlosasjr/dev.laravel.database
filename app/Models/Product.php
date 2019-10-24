<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'flag', 'description', 'price', 'image'];


    /**
     * Relacionamento Many to Many
     * Produtos e pedidos-Item(Sales)
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'sales');
    }



    /**
     * Relacionamento One to Many - Um para muitos
     * Traz todos os itens(detalhe) da tabela evaluations que pertencem ao produto
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'product_id', 'id');
    }


}
