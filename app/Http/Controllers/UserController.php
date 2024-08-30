<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Post;
use App\Models\Category;
use App\Models\Judgement;
use App\Models\Like;
use App\Http\Requests\UserRequest;
use Cloudinary;

class UserController extends Controller
{
    public function show(User $user)
    {
        $categories = Category::all();
        $posts = $user->posts()->with('category')->get()->keyBy('category_id');
    
        return view('users.show', compact('user', 'categories', 'posts'));
    }
    public function edit(User $user)
    {
        return view('users.edit')->with(['user' => $user]);
    }
    public function update(Request $request, User $user)
    {
        $input = $request->only(['name', 'profile_image']);
        
        if (isset($input['name']) && $input['name'] !== $user->name) {
            $user->name = $input['name'];
        }

        if ($request->hasFile('profile_image')) {
            $uploadedImage = $request->file('profile_image');
            $newProfileImageUrl = Cloudinary::upload($uploadedImage->getRealPath())->getSecurePath();
        
            if ($user->profile_image !== $newProfileImageUrl) {
                $user->profile_image = $newProfileImageUrl;
            }
        }
        $user->save();
        
        return redirect('/users/' .$user->id);
    }
}
