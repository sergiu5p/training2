<?php

namespace App\Http\Controllers;

use App\Mail\sendMail;
use App\Order;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['bail',
                'required',
                'string',
            ],

            'email' => ['required',
                    'email',
                ],

            'comments' => [
                'required',
            ]
        ]);

        $insert = Order::query()->create(
            [
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'comments' => $validatedData['comments']
            ]
        );
        $lastInsertId = $insert->id;

        $products = Product::query()
            ->whereIn('id', data_get($request->session()->get('cart'), 'items'))
            ->get();

        $products->each(function ($product) use ($lastInsertId) {
           DB::table('order_product')->insert(
               [
                    'order_id' => $lastInsertId,
                    'product_id' => $product->id
                ]
           );
        });

        Mail::to(env('ADMIN_EMAIL', 'purcariu.sergiu@gmail.com'))->send(
            new sendMail($validatedData['name'], $validatedData['email'], $validatedData['comments'])
        );
        $request->session()->forget('cart');

        if ($request->ajax()) {
            return ['success' => true];
        }

        return redirect()->route('product.show');
    }

    public function index(Request $request)
    {
        $orders = Order::with('products')->get();

        if ($request->ajax()) {
            return $orders;
        }

        return view('orders.orders', compact('orders'));
    }

    public function show(Request $request, $id)
    {
        $order = Order::with('products')->findOrFail($id);
        
        if ($request->ajax()) {
            return $order;
        }

        return view('orders.order', compact('order'));
    }
}
