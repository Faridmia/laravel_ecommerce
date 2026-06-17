<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function home()
    {
        $data['meta_title'] = 'E-commerce';
         $data['meta_description'] = 'This is home page description';
        $data['meta_description'] = 'This is home page description';
        $data['meta_keywords'] = 'home, page, keywords';
        return view('home');
    }
}
