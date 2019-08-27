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

    public function add($item, $id)
    {
        $storedItem = ['price' => $item->price, 'item' => $item];
        $storedItem['price'] = $item->price;
        $this->items[$id] = $storedItem;
    }

}
