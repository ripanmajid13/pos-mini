<?php

namespace App\View\Components\Layouts;

use App\Models\{Setting, Navigation, Profile};
use Illuminate\View\Component;

class SidebarMain extends Component
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

        return view('components.layouts.sidebar-main', [
            'title' => Setting::find(1)->description,
            'logo' => Setting::find(3)->description ? asset('images/'.Setting::find(3)->description) : asset('images/default/icon.png'),
            'userImage' => $profile->get()->count() ? (Profile::find($profile->first()->id)->image ? asset('images/'.Profile::find($profile->first()->id)->image) : asset('images/default/user.png')) : asset('images/default/user.png'),
            'navigations' => Navigation::with('children')->whereNull('parent_id')->whereNotIn('name', ['setting'])->orderBy('position', 'asc')->get()
        ]);
    }
}
