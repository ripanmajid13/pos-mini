<?php

namespace App\View\Components\Layouts;

use App\Models\Setting;
use Illuminate\View\Component;

class AuthHeader extends Component
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
        return view('components.layouts.auth-header', [
            'title' => Setting::find(1)->description
        ]);
    }
}
