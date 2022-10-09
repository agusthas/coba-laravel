<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;

class PostController extends Controller
{
    public function index()
    {
        $title = '';

        if (request('category')) {
            $category = Category::firstWhere('slug', request('category'))->name;
            $title = ' in ' . $category;
        }

        if (request('author')) {
            $author = User::whereUsername(request('author'))->first()->name;
            $title = ' by ' . $author;
        }

        return view('posts', [
            "title" => "All Posts" . $title,
            "active" => "posts",
            "posts" => Post::latest()->filter(request(['search', 'category', 'author']))->paginate(7)->withQueryString()
        ]);
    }

    public function show(Post $post)
    {
        return view('post', [
            "title" => "Single Post",
            "active" => "posts",
            "post" => $post
        ]);
    }
}
