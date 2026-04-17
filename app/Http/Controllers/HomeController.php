<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function show(){
        $category = 'Mouse';
        $button = '<button>Click me</button>';
        return view('home',[
            'product_category' => $category,
            'product_name' => 'Logitech G502 Hero',
            'button' => $button
        ]);
    }
}
