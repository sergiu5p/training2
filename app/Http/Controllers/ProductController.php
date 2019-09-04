<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $product = Product::query()->findOrFail($id);
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
        if ($request->session()->has('cart') && $request->session()->get('cart')->items) {
            $products = Product::all()->whereIn('id', $request->session()->get('cart')->items);
            return view('products.cart', compact('products'));
        }
        return redirect()->route('product.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id = null)
    {
        if (!$request->session()->has('login')) {
            return redirect()->route('login');
        }
        $product = $id ? Product::query()->findOrFail($id) : new Product();
        return view('products.product', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id = null)
    {
        if (!$request->session()->has('login')) {
            return redirect()->route('login');
        }

        $unique = Rule::unique('products');
        if ($id) {
            $unique = $unique->ignore(Product::query()->findOrFail($id));
        }

        $validatedData = $request->validate([
            'title' => ['bail',
                        'required',
                        'max:255',
                        $unique,
                        ],

            'description' => ['required'],

            'price' => [
                        'required',
                        'numeric'
                        ],

            'image' => [
                        'image',
                        !$id ? 'required' : ''
                        ],
        ]);

        if ($id) {
            if (isset($validatedData['image'])) {
                $product = Product::query()->findOrFail($id);
                File::delete(public_path() . '/images/' . $product->id . '.' . $product->image_extension);
                $validatedData['image']->move(public_path() . '/images/', $product->id . '.' . $validatedData['image']->extension());
                Product::query()->findOrFail($id)->update(['image_extension' => $validatedData['image']->extension()]);
            }

            Product::query()->findOrFail($id)->update(
                    [
                        'title' => $validatedData['title'],
                        'description' => $validatedData['description'],
                        'price' => $validatedData['price']
                    ]);

        } else {
            $product = Product::query()->create(
                [
                    'title' => $validatedData['title'],
                    'description' => $validatedData['description'],
                    'price' => $validatedData['price'],
                    'image_extension' => $validatedData['image']->extension()
                ]
            );
            $validatedData['image']->move(public_path() . '/images/', $product->id . '.' . $validatedData['image']->extension());
        }

        return redirect()->route('product.products');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function removeFromCart(Request $request, $id)
    {
        if (!$request->session()->has('login')) {
            return redirect()->route('login');
        }
        $index = array_search($id, $request->session()->get('cart')->items);

        if ($index !== false) {
            unset($request->session()->get('cart')->items[$index]);
        }
        return back();
    }

    public function products(Request $request)
    {
        if (!$request->session()->has('login')) {
            return redirect()->route('login');
        }
        $products = Product::all();
        return view('products.products', compact('products'));
    }

    public function destroy(Request $request, $id)
    {
        if (!$request->session()->has('login')) {
            return redirect()->route('login');
        }

        $product = Product::query()->findOrFail($id, ['id', 'image_extension']);
        File::delete(public_path() . '/images/' . $product->id . '.' . $product->image_extension);

        Product::query()->where('id', $id)->delete();
        return back();
    }
}
