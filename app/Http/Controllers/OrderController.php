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
        if (!$request->session()->has('login')) {
            return redirect()->route('login');
        }

        /*$orders = DB::table(DB::raw('order_product'))
            ->select(DB::raw('orders.*'), DB::raw("SUM(products.price) AS summed_price"))
            ->leftJoin(DB::raw('orders'), DB::raw('order_product.order_id'), '=', DB::raw('orders.id'))
            ->leftJoin(DB::raw('products'), DB::raw('order_product.product_id'), '=', DB::raw('products.id'))
            ->groupBy(DB::raw('order_product.order_id'))->get();*/

        $orders = Order::with('products')->get();

        if ($request->ajax()) {
            return $orders;
        }

        return view('orders.orders', compact('orders'));
    }

    public function show(Request $request, $id)
    {
        if (!$request->session()->has('login')) {
            return redirect()->route('login');
        }

        // $order = DB::table(DB::raw('order_product'))
        //     ->select(DB::raw('products.title'), DB::raw('orders.name'), DB::raw('orders.email'), DB::raw('orders.comments'))
        //     ->rightJoin(DB::raw('products'), DB::raw('order_product.product_id'), '=', DB::raw('products.id'))
        //     ->rightJoin(DB::raw('orders'), DB::raw('order_product.order_id'), '=', DB::raw('orders.id'))
        //     ->where(DB::raw('order_product.order_id'), '=', DB::raw($id))->get();

        $order = Order::with('products')->findOrFail($id);
        
        if ($request->ajax()) {
            return $order;
        }

        return view('orders.order', compact('order'));
    }
}
