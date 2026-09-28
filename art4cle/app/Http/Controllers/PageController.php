<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        return view('home');
    }

    public function admin()
    {
        return view('admin');
    }

    public function category()
    {
        return view('category');
    }

    public function journalist()
    {
        return view('journalist');
    }
}
