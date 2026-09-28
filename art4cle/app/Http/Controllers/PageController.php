<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        $articles = [
            ['title' => 'Столичный Врач', 'category' => 'Люди', 'text' => 'Эпидемию можно лечить наукой'],
            ['title' => 'Столичный Врач', 'category' => 'Люди', 'text' => 'Эпидемию можно лечить наукой'],
            ['title' => 'Столичный Врач', 'category' => 'Люди', 'text' => 'Эпидемию можно лечить наукой']
        ];
        return view('home', ['articles' => $articles]);
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
