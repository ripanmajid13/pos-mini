<?php

namespace App\Http\Controllers;

use App\Models\{Role, Navigation, IncomingItem, OutgoingItem};
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function link()
    {
        return request()->segment(1).'.';
    }

    public function folder()
    {
        $nav = Navigation::where('url', request()->segment(1))->first();
        if ($nav->parent) {
            $folder = $nav->folder.'.'.strtolower(str_replace(' ', '-', $nav->parent->name)).'.'.$nav->url;
        } else {
            $folder = $nav->folder.'.'.$nav->url;
        }
        return $folder.'.';
    }

    public function can($data)
    {
        $can = request()->segment(1);
        switch ($data) {
            case 'create':
                return $can.'-create';
                break;
            case 'edit':
                return $can.'-edit';
                break;
            case 'delete':
                return $can.'-delete';
                break;
            case 'roles':
                return $can.'-roles'; // only for guest
                break;

            default:
                return '';
                break;
        }
    }

    public function dateYmd($data)
    {
        $ex = explode('/', $data);
        return $ex[2].'-'.$ex[1].'-'.$ex[0];
    }

    public function getRoles()
    {
        if(auth()->user()->hasRole('developer')) {
            return Role::orderBy('name', 'asc')->get();
        } else {
            return Role::whereNotIn('name', ['developer'])->orderBy('name', 'asc')->get();
        }
    }

    public function navigations()
    {
        if(auth()->user()->hasRole('developer')) {
            return Navigation::with(['parent' => function($query) {
                $query->orderBy('name', 'asc');
            }])
            ->whereNotNull('url')
            ->orderByRaw('ISNULL(parent_id), name ASC')
            ->get();
        } else {
            return Navigation::with(['parent' => function($query) {
                $query->orderBy('name', 'asc');
            }])
            ->whereNotNull('url')
            ->whereNotIn('url', ['role-has-permission', 'user-has-permission', 'setting'])
            ->orderByRaw('ISNULL(parent_id), name ASC')
            ->get();
        }
    }

    public function countNav($id)
    {
        $data = 0;
        foreach ($this->navigations() as $navigation) {
            $data += Role::findOrFail($id)->permissions()->find($navigation->permissions->first()['id']) ? 1 : 0;
        }
        return $data;
    }

    //-----------------------------------

    public function code($model, $date)
    {
        if ($model == 'TBM') {
            $check = IncomingItem::where('date',  $this->dateYmd($date))->orderBy('code', 'desc');
            $date = str_replace('-', '', $this->dateYmd($date));
            if ($check->get()->count()) {
                $getNo = str_replace('TBM-'.$date, '', $check->first()->code)+1;
                $code = 'TBM-'.$date.sprintf("%04d", $getNo);
            } else {
                $code = 'TBM-'.$date.sprintf("%04d", 1);
            }
        } else  if ($model == 'TBK') {
            $check = OutgoingItem::where('date',  $this->dateYmd($date))->orderBy('code', 'desc');
            $date = str_replace('-', '', $this->dateYmd($date));
            if ($check->get()->count()) {
                $getNo = str_replace('TBK-'.$date, '', $check->first()->code)+1;
                $code = 'TBK-'.$date.sprintf("%04d", $getNo);
            } else {
                $code = 'TBK-'.$date.sprintf("%04d", 1);
            }
        } else {
            $code = '';
        }

        return $code;
    }
}
