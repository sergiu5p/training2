<?php

namespace App;

class Cart
{
    public $items = null;

    public function __construct($oldCart)
    {
        if ($oldCart) {
            $this->items = $oldCart->items;
        }
    }

    public function add($id)
    {
        $this->items[] = $id;
    }

}
