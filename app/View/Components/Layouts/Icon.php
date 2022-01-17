<?php

namespace App\View\Components\Layouts;

use App\Models\Setting;
use Illuminate\View\Component;

class Icon extends Component
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
        return view('components.layouts.icon', [
            'logo' => Setting::find(3)->description ? asset('images/'.Setting::find(3)->description) : asset('images/default/icon.png'),
        ]);
    }
}
