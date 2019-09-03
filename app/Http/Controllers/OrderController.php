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

    public function sendMail(Request $request)
    {
        $insert = Order::query()->create(
            [
                'name' => $request->name,
                'email' => $request->email,
                'comments' => $request->comments
            ]
        );
        $lastInsertId = $insert->id;

        $products = Product::all()->whereIn('id', $request->session()->get('cart')->items);

        $products->each(function ($product) use ($lastInsertId) {
           DB::table('order_product')->insert(
               [
                    'order_id' => $lastInsertId,
                    'product_id' => $product->id
                ]
           );
        });

        $this->validate($request, [
            "name" => "required|string",
            "email" => "required|email",
            "comments" => "required"
        ]);

        $name = $request->name;
        $email = $request->email;
        $comments = $request->comments;

        Mail::to('purcariu.sergiu@gmail.com')->send(
            new sendMail($name, $email, $comments)
        );
        $request->session()->forget('cart');
        return redirect()->route('product.show');
    }

    public function showOrders(Request $request)
    {
        if (!$request->session()->has('login')) {
            return redirect()->route('login');
        }

        $orders = DB::table(DB::raw('order_product'))
            ->select(DB::raw('orders.*'), DB::raw("SUM(products.price) AS summed_price"))
            ->join(DB::raw('orders'), DB::raw('order_product.order_id'), '=', DB::raw('orders.id'))
            ->join(DB::raw('products'), DB::raw('order_product.product_id'), '=', DB::raw('products.id'))
            ->groupBy(DB::raw('order_product.order_id'))->get();

        return view('orders.orders', compact('orders'));
    }
}
