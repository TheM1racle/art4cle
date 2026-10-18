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
        $categories = Category::all();
        return view('journalist', ['categories' => $categories]);
}

    public function store(Request $request)
    {

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'text' => 'required|string',
        ]);


        Article::create($data);


        return redirect('/');
}
}
