<?php

namespace App\Models;

use App\Models\Navigation;
use App\Models\Permission;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getNavigations($data)
    {
        $navigations = array();
        foreach($data as $item) {
            $nav = Navigation::find($item);
            $navigations[] = empty($nav->parent->name) ? $nav->name : $nav->parent->name.' '.$nav->name;
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
            $actions[] = empty($nav->parent->name) ? '' : $nav->parent->name.' '.ucwords(str_replace('-', ' ', $item));
        }
        sort($actions);
        return implode('<br /> ', $actions);
    }
}
