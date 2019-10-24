<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $fillable = ['stars', 'testimony'];

    /**
     * Relacionamento Many to One - Muitos para um
     * Retorna o produto(mestre) deste item
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /**
     * Relacionamento Many to One - Muitos para um
     * Retorna o usuário deste item
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
