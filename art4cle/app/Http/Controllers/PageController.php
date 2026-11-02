<?php

namespace App\Http\Controllers;

use App\Models\Text;
use App\Models\Category;
use Illuminate\Http\Request;





class PageController extends Controller
{
    public function home()
    {
        $texts = Text::with(['category', 'author'])->latest()->get();
        return view('home', ['texts' => $texts]);
    }

    public function category()
    {
        $category = Category::where('name', 'Хронология')->first();
        $texts = $category ? $category->texts()->latest()->get() : collect();
        return view('category', ['texts' => $texts]);
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
            'heading' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'text' => 'required|string',
        ]);

        $request->user()->texts()->create($data);

        return redirect('/');
    }
}
