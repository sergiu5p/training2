<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Requests\CreateProduct;
use App\Http\Requests\EditProduct;

class ProductController extends Controller
{

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
            $products = Product::query()
                ->whereNotIn('id', data_get($request->session()->get('cart'), 'items'))
                ->get();
        } else {
            $products = Product::all();
        }

        if ($request->ajax()) {
            return $products;
        } else {
            return view('products.index', compact('products'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Product|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function edit(Request $request, $id)
    {
        $product = Product::query()->findOrFail($id);

        if ($request->ajax()) {
            return $product;
        }

        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return array
     */
    public function update(EditProduct $request, $id)
    {
        $validatedData = $request->validated();

        $toUpdate = [
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'price' => $validatedData['price'],
        ];

        if (isset($validatedData['image'])) {
            $toUpdate  = array_merge($toUpdate, ['image_extension' => $validatedData['image']->extension()]);
        }
        $product = Product::query()->findOrFail($id);
        
        if (isset($validatedData['image'])) {
            File::delete(public_path('/images/') . $product->id . '.' . $product->image_extension);
            $validatedData['image']->move(public_path('/images/'), $product->id . '.' . $validatedData['image']->extension());
        }
        $product->fill($toUpdate);
        $product->save();

        if ($request->ajax()) {
            return ['success' => true];
        }

        return redirect()->route('product.products');
    }

    public function create(Request $request)
    {
        return view('products.create');
    }

    public function store(CreateProduct $request)
    {
        $validatedData = $request->validated();

        $product = Product::query()->create(
            [
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
                'price' => $validatedData['price'],
                'image_extension' => $validatedData['image']->extension()
            ]
        );
        $validatedData['image']->move(public_path('/images/'), $product->id . '.' . $validatedData['image']->extension());

        if ($request->ajax()) {
            return ['success' => true];
        }

        return redirect()->route('product.products');
    }

    public function products(Request $request)
    {
        $products = Product::all();
        if ($request->ajax()) {
            return $products;
        }

        return view('products.products', compact('products'));
    }

    public function destroy(Request $request, $id)
    {
        $product = Product::query()->findOrFail($id, ['id', 'image_extension']);
        File::delete(public_path('/images/'). $product->id . '.' . $product->image_extension);

        Product::query()->where('id', $id)->delete();

        if ($request->ajax()) {
            return ['success' => true];
        }

        return back();
    }
}
