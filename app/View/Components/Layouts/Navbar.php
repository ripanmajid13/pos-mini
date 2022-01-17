<?php

namespace App\View\Components\Layouts;

use App\Models\Profile;
use Illuminate\View\Component;

class Navbar extends Component
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
        $profile = Profile::where('user_id', auth()->user()->id);
        return view('components.layouts.navbar', [
            'userImage' => $profile->get()->count() ? (Profile::find($profile->first()->id)->image ? asset('images/'.Profile::find($profile->first()->id)->image) : asset('images/default/user.png')) : asset('images/default/user.png')
        ]);
    }
}
