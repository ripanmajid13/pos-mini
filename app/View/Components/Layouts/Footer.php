<?php

namespace App\View\Components\Layouts;

use App\Models\Setting;
use Illuminate\View\Component;

class Footer extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.layouts.footer', [
            'copyright' => Setting::find(2)->description,
        ]);
    }
}
