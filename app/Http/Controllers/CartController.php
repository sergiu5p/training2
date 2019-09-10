<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        if ($request->session()->has('cart')) {
            $products = Product::query()
                ->whereIn('id', data_get($request->session()->get('cart'), 'items'))
                ->get();

            if ($request->ajax()) {
                return $products;
            } else {
                return view('products.cart', compact('products'));
            }
        }

        if ($request->ajax()) {
            return [];
        } else {
            return redirect()->route('product.show');
        }
    }

    public function store(Request $request)
    {
        $data = $request->all('product_id');
        $product = Product::query()->findOrFail($data['product_id']);
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->add($product->id);
        $request->session()->put('cart', $cart);

        if ($request->ajax()) {
            return [
                'success' => true,
                'productsInCart' => data_get($request->session()->get('cart'), 'items')
                ];
        }

        return redirect()->route('product.show');
    }

    public function destroy(Request $request, $id)
    {
        $index = array_search($id, data_get($request->session()->get('cart'), 'items'));

        if ($index !== false) {
            unset($request->session()->get('cart')->items[$index]);
        }

        if ($request->ajax()) {
            return [
                'success' => true,
                'productsInCart' => data_get($request->session()->get('cart'), 'items')
            ];
        }
        return back();
    }
}
