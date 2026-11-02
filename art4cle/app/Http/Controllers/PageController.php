<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Role;
use App\Models\Text;
use App\Models\User;
use Illuminate\Http\Request;

class PageController extends Controller
{

    public function home()
    {
        $texts = Text::with(['category', 'author'])
            ->where('is_blocked', false)
            ->latest()
            ->get();

        return view('home', ['texts' => $texts]);
    }


    public function category()
    {
        $category = Category::where('name', 'Хронология')->first();
        $texts = $category
            ? $category->texts()->where('is_blocked', false)->latest()->get()
            : collect();

        return view('category', ['texts' => $texts]);
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


    public function admin()
    {
        $texts = Text::with(['category', 'author'])->latest()->get();
        $users = User::with('role')->get();
        $roles = Role::all();

        return view('admin', [
            'texts' => $texts,
            'users' => $users,
            'roles' => $roles,
        ]);
    }


    public function edit(Text $text)
    {
        $categories = Category::all();
        return view('admin.edit', ['text' => $text, 'categories' => $categories]);
    }


    public function update(Request $request, Text $text)
    {
        $data = $request->validate([
            'heading' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'text' => 'required|string',
        ]);

        $text->update($data);

        return redirect('/admin');
    }


    public function block(Text $text)
    {
        $text->is_blocked = !$text->is_blocked;
        $text->save();

        return redirect('/admin');
    }


    public function destroy(Text $text)
    {
        $text->delete();

        return redirect('/admin');
    }


    public function changeRole(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::findOrFail($data['user_id']);
        $user->role_id = $data['role_id'];
        $user->save();

        return redirect('/admin');
    }
}
