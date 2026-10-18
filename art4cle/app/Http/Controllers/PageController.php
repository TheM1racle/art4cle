<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;





class PageController extends Controller
{
    public function home()
    {
        $articles = Article::with('category')->latest()->get();
        return view('home', ['articles' => $articles]);
    }

    public function category()
    {
        $category = Category::where('name', 'Хронология')->first();
        $articles = $category->articles()->latest()->get();
        return view('category', ['articles' => $articles]);
    }

    public function admin()
    {
        return view('admin');
    }


    public function journalist()
    {
        return view('journalist');
    }
}
