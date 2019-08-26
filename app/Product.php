<?php

namespace App;

use Illuminate\Contracts\Session\Session;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function __construct()
    {
        Session::push('cart', []);
    }
}
