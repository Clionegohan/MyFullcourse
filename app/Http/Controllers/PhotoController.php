<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use App\Models\DiaryImage;

class PhotoController extends Controller
{
    public function index()
    {
        $images = Image::All();
        $diaryImages = DiaryImage::All();
        
        return view('photos.index', compact('images', 'diaryImages'));
    }
}
