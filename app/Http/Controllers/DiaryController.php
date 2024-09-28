<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Diary;
use App\Models\Diary_image;
use App\Models\DiaryLike;
use App\Models\DiaryComment;
use App\Http\Requests\DiaryRequest;
use App\Http\Requests\DiaryCommentRequest;
use Cloudinary;

class DiaryController extends Controller
{
    public function index(Diary $diary) {
        $diaries = $diary->getPaginateBylimit();
        return view('diaries.index')->with(['diaries' => $diaries]);
    }
    
    public function create() {
        $user = auth()->user();
        return view('diaries.create');
    }
    
    public function store(Diary $diary, DiaryRequest $request) {
        $input = $request->input('diary');
        $user = auth()->user();
        
        $diary->fill($input);
        $diary->user_id = $user->id;
        $diary->save();
        
        if ($request->hasFile('files')) {
            $images = $request->file('files');
            
            foreach ($images as $image) {
                $image_url = Cloudinary::upload($image->getRealPath())->getSecurePath();
                $diary->images()->create(['image_url' => $image_url]);
            }
        }
        return redirect('/diaries/' . $diary->id);
    }
    
    public function show(Diary $diary) {
        $images = $diary->images;
        //isLikedByUser
        return view('diaries.show', compact('diary', 'images'));
        
    }
    
    /*
    
    public function edit(Diary $diary) {
        
        return view('diaries.edit')->with(['diary'=> $diary]);
    }
    
    public function update(Diary $diary, DiaryRequest $request) {
        
        $input = $request->input('diary');
        $diary = fill($input);
        $diary->save();
        
        if ($request->has('new_images')) {
            foreach ($request->file('new_images') as $newImage) {
                $image_url = Cloudinary::upload($newImage->getRealPath())->getSecurePath();
                $diary->images()->create(['image_url' => $image_url]);
            }
        }
        
        if ($request->has('delete_images')) {
            foreach ($request->input('delete_images') as $imageId)
            $image = DiaryImage::find($imageId);
            if($image) {
                $image->delete();
            }
        }
        return redirect('/diaries/' .$diary->id);
    }
    
    */
    
    public function delete(Diary $diary) {
        
        $user = auth()->user();
        
        if ($diary->images->isNotEmpty()) {
            foreach ($diary->images as $image) {
                $image->delete();
            }
        }
        $diary->delete();
        return redirect()->route('diary.index');
    }
}
