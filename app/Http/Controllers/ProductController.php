<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $product = Product::find($id);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->add($product->id);
        $request->session()->put('cart', $cart);

        return redirect()->route('product.show');
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        if ($request->session()->has('cart')) {
            $products = Product::all()->whereNotIn('id', $request->session()->get('cart')->items);
        } else {
            $products = Product::all();
        }

        return view('products.index', compact('products'));
    }

    public function showCart(Request $request)
    {
        if ($request->session()->has('cart')) {
            $products = Product::all()->whereIn('id', $request->session()->get('cart')->items);
        } else {
            $products = Product::all();
        }

        return view('products.cart', compact('products'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $index = array_search($id, $request->session()->get('cart')->items);

        if ($index !== false) {
            unset($request->session()->get('cart')->items[$index]);
        }
        return back();
    }
}
