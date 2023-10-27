<?php

namespace App\Http\Controllers;

use App\Models\Gallery;

class HomeController extends Controller
{
    public function index()
    {
        $sliders = Gallery::typeSlider()->orderBy('sort')->active()->get();

        return view('home', compact('sliders'));
    }
}
