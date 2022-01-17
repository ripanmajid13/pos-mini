<?php

namespace App\Models;

use App\Models\Navigation;
use App\Models\Permission;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as Model;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function getNavigations($data)
    {
        $navigations = array();
        foreach($data as $item) {
            $nav = Navigation::find($item);
            // if (auth()->user()->hasRole('developer')) {
                $navigations[] = empty($nav->parent->name) ? $nav->name : $nav->parent->name.' '.$nav->name;
            // } else {
            //     if ($nav->url != 'role-has-permission' && $nav->url != 'user-has-permission') {
            //         $navigations[] = empty($nav->parent->name) ? $nav->name : $nav->parent->name.' '.$nav->name;
            //     }
            // }
        }
        sort($navigations);
        return implode('<br /> ', $navigations);
    }

    public function getActions($data)
    {
        $actions = array();
        foreach($data as $item => $value) {
            $per = Permission::find($value);
            $nav = Navigation::find($per->navigation_id);
            // if (auth()->user()->hasRole('developer')) {
                $actions[] = empty($nav->parent->name) ? '' : $nav->parent->name.' '.ucwords(str_replace('-', ' ', $item));
            // } else {
            //     if ($nav->url != 'role-has-permission') {
            //         $actions[] = empty($nav->parent->name) ? '' : $nav->parent->name.' '.ucwords(str_replace('-', ' ', $item));
            //     }
            // }
        }
        sort($actions);
        return implode('<br /> ', $actions);
    }
}
