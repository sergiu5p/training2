<?php

namespace App\Mail;

use App\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class sendMail extends Mailable
{
    use Queueable, SerializesModels;
    public $name;
    public $email;
    public $comments;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $email, $comments)
    {
        $this->name = $name;
        $this->email = $email;
        $this->comments = $comments;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(Request $request)
    {
        $products = Product::all()->whereIn('id', $request->session()->get('cart')->items);
        $message = '';
ss
        foreach ($products as $product)
        {
            $message .= "<img src=".'"'."http"."://".$_SERVER['HTTP_HOST']."/images/".$product->id.'.'.$product->image_extension.'"'.">";
            $message .= "<h4>$product->title</h4>";
            $message .= "<h4>$product->description</h4>";
            $message .= "<h4>$product->price</h4>";
        }

        $message .= "<h4>$this->name</h4>";
        $message .= "<h4>$this->email</h4>";
        $message .= "<h4>$this->comments</h4>";
        return $this->markdown('email.message', compact('message'));
    }
}
