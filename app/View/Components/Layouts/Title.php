<?php

namespace App\View\Components\Layouts;

use App\Models\Setting;
use Illuminate\View\Component;

class Title extends Component
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
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        $title = request()->segment(1) ? ' - '.ucwords(str_replace('-', ' ', request()->segment(1))) : '';
        $title = Setting::find(1)->description.$title;
        return view('components.layouts.title', [
            'title' => $title
        ]);
    }
}
