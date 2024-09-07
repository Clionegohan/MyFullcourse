<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index(Category $category)
    {
        $posts = $category->getByCategory();
        return view('categories.index')->with([
            'posts' => $posts,
            'category' => $category
            ]);
    }
}

