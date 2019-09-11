<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        "name",
        "email",
        "comments"
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
