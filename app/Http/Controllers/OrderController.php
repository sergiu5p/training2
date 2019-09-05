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
        $data = $request->all(['name', 'email', 'comments']);
        $insert = Order::query()->create(
            [
                'name' => $request->name,
                'email' => $request->email,
                'comments' => $request->comments
            ]
        );
        $lastInsertId = $insert->id;

        // comment
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

        $this->validate($request, [
            "name" => "required|string",
            "email" => "required|email",
            "comments" => "required"
        ]);

        $name = $request->name;
        $email = $request->email;
        $comments = $request->comments;

        Mail::to(env('ADMIN_EMAIL', 'purcariu.sergiu@gmail.com'))->send(
            new sendMail($name, $email, $comments)
        );
        $request->session()->forget('cart');
        return redirect()->route('product.show');
    }

    public function index(Request $request)
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

    public function show(Request $request, $id)
    {
        if (!$request->session()->has('login')) {
            return redirect()->route('login');
        }

        $order = DB::table(DB::raw('order_product'))
            ->select(DB::raw('products.title'), DB::raw('orders.name'), DB::raw('orders.email'), DB::raw('orders.comments'))
            ->rightJoin(DB::raw('products'), DB::raw('order_product.product_id'), '=', DB::raw('products.id'))
            ->rightJoin(DB::raw('orders'), DB::raw('order_product.order_id'), '=', DB::raw('orders.id'))
            ->where(DB::raw('order_product.order_id'), '=', DB::raw($id))->get();

        return view('orders.order', compact('order'));
    }
}
