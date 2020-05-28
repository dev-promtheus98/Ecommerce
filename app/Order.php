<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    protected $fillables = ['payment_intent_id', 'amount', 'payment_created_at', 'products', 'user_id'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
