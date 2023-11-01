<?php

namespace App\View\Components\Home;

use App\Models\Gallery;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Banners extends Component
{
    public function render(): View
    {
        $sliders = Gallery::typeSlider()->orderBy('sort')->active()->get();

        return view('components.home.banners', compact('sliders'));
    }
}
