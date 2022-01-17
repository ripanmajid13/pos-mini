<?php

namespace App\Http\Controllers\Privileges;

use App\Models\{Permission, Role, RoleHasPermission};
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;

class RoleHasPermissionController extends Controller
{
    private function nav($id, $variable)
    {
        switch ($variable) {
            case 'role-has-permission':
                $p = Role::findOrFail($id)->permissions->whereNull('parent_id')->pluck('id')->toArray();
                $rhs = Permission::where('name', $this->link)->first()->id;
                $nrhs = count(array_intersect(["$rhs"], $p));
                return $nrhs ? $rhs : 0;
            break;

            case 'role-has-permission-create':
                $cc = Role::findOrFail($id)->permissions->whereNull('navigation_id')->where('name', $this->link.'-create')->pluck('id')->toArray();
                $rhc = Permission::where('name', $this->link)->first()->children->where('name', $this->link.'-create')->first()->id;
                $arhc = count(array_intersect(["$rhc"], $cc));
                return $arhc ? $rhc : 0;
            break;

            case 'role-has-permission-edit':
                $ce = Role::findOrFail($id)->permissions->whereNull('navigation_id')->where('name', $this->link.'-edit')->pluck('id')->toArray();
                $rhe = Permission::where('name', $this->link)->first()->children->where('name', $this->link.'-edit')->first()->id;
                $arhe = count(array_intersect(["$rhe"], $ce));
                return $arhe ? $rhe : 0;
            break;

            case 'user-has-permission':
                $p = Role::findOrFail($id)->permissions->whereNull('parent_id')->pluck('id')->toArray();
                $uhs = Permission::where('name','user-has-permission')->first()->id;
                $nuhs = count(array_intersect(["$uhs"], $p));
                return $nuhs ? $uhs : 0;
            break;

            case 'user-has-permission-create':
                $cc = Role::findOrFail($id)->permissions->whereNull('navigation_id')->where('name', 'user-has-permission-create')->pluck('id')->toArray();
                $uhc = Permission::where('name', 'user-has-permission')->first()->children->where('name', 'user-has-permission-create')->first()->id;
                $auhc = count(array_intersect(["$uhc"], $cc));
                return $auhc ? $uhc : 0;
            break;

            case 'user-has-permission-edit':
                $ce = Role::findOrFail($id)->permissions->whereNull('navigation_id')->where('name', 'user-has-permission-edit')->pluck('id')->toArray();
                $uhe = Permission::where('name', 'user-has-permission')->first()->children->where('name', 'user-has-permission-edit')->first()->id;
                $auhe = count(array_intersect(["$uhe"], $ce));
                return $auhe ? $uhe : 0;
            break;

            case 'setting':
                $p = Role::findOrFail($id)->permissions->whereNull('parent_id')->pluck('id')->toArray();
                $set = Permission::where('name', 'setting')->first()->id;
                $nset = count(array_intersect(["$set"], $p));
                return $nset ? $set : 0;
            break;

            default:
                return 0;
                break;
        }
    }

    public function index()
    {
        return view($this->folder().'index', [
            'urlTable'  => route($this->link().'table'),
            'urlCreate' => route($this->link().'create'),
            'canCreate' => $this->can('create'),
            'canEdit'   => $this->can('edit'),
            'roles'     => Role::doesnthave('permissions')->whereNotIn('name', ['developer'])->get()->count(),
        ]);
    }

    public function create()
    {
        $html = view($this->folder().'_create', [
            'column'        => new Role,
            'url'           => route($this->link().'store'),
            'method'        => 'POST',
            'roles'         => Role::select(['id', 'name', 'guard_name'])
                                ->doesnthave('permissions')
                                ->whereNotIn('name', ['developer'])
                                ->orderBy('name', 'asc')
                                ->get(),
            'navigations'   => $this->navigations()
        ])->render();

        return json_encode(
            array(
                'title' => 'Navigation',
                'html' => $html
            )
        );
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role'         => ['required'],
            'navigations'   => ['array'],
        ]);

        if ($validator->fails()) {
            return json_encode(array('sts' => 'errors', 'errors' => $validator->errors()));
        }

        Role::find(request('role'))->givePermissionTo(request('navigations'));
        if(!empty(request('navigations'))) {
            foreach(request('navigations') as $permission_id){
                RoleHasPermission::where('permission_id', $permission_id)->where('role_id', request('role'))
                                    ->update([
                                        'created_by' => auth()->user()->id,
                                        'created_at' => Carbon::now()->format('Y-m-d H:i:s')
                                    ]);
            }
        }

        return json_encode(
            array(
                'sts'   => 'success',
                'msg'   => 'Berhsil disimpan.',
                'scs'   => 'store',
                'nav'   => Role::doesnthave('permissions')->whereNotIn('name', ['developer'])->get()->count()
            )
        );
    }

    public function show($id, $slug)
    {
        $html = view($this->folder().'_show', [
            'column'        => Role::findOrFail($id),
            'url'           => route($this->link().'update', [$id, $slug]),
            'method'        => 'PUT',
            'slug'          => $slug == 'nav' ? 1 : 0,
            'navigations'   => $this->navigations()
        ])->render();

        return json_encode(
            array(
                'title' => $slug == 'nav' ? 'Navigation' : 'Action',
                'html' => $html
            )
        );
    }

    public function update(Request $request, $id, $slug)
    {
        if ($slug == 'nav') {
            $validator = Validator::make($request->all(), [
                'navigations'   => ['array'],
            ]);

            if ($validator->fails()) {
                return json_encode(array('sts' => 'errors', 'errors' => $validator->errors()));
            }

            // if (!auth()->user()->hasRole('developer')) {
            //     $role = Role::findOrFail($id);
            //     if (!count(array_intersect(auth()->user()->getRoleNames()->toArray(), ["$role->name"]))) {
            //         $per = Permission::where('name', request()->segment(1))->first();
            //         if (empty(count(array_intersect(request('navigations'), ["$per->id"])))) {
            //             return json_encode(
            //                 array(
            //                     'sts' => 'warning',
            //                     'msg' => 'Gagal diperbaharui, '.ucwords(str_replace('-', ' ', $per->name)).' tidak boleh dihapus.'
            //                 )
            //             );
            //         }
            //     }
            // }

            if (auth()->user()->hasRole('developer')) {
                $navigations = request('navigations');
            } else {
                $rhs = $this->nav($id, 'role-has-permission');
                $uhs = $this->nav($id, 'user-has-permission');
                $set = $this->nav($id, 'setting');
                if ($rhs+$uhs) {
                    if ($rhs) { $ru[] = "$rhs"; }
                    if ($uhs) { $ru[] = "$uhs"; }
                    if ($set) { $ru[] = "$set"; }
                    if (empty(request('navigations'))) {
                        $navigations = $ru;
                    } else {
                        $navigations = array_merge(request('navigations'), $ru);
                    }
                } else {
                    $navigations = request('navigations');
                }
            }

            $rhps = RoleHasPermission::where('role_id', $id)->get();
            foreach($rhps as $rhp) {
                // detach
                if(empty($rhp->permission->parent_id)) {
                    if(empty(count(array_intersect($navigations ?? ["0"] , ["$rhp->permission_id"])))) {
                        // child
                        foreach($rhp->permission->children as $key){
                            Role::find($id)->permissions()->detach($key->id);
                        }
                        //parent
                        Role::find($id)->permissions()->detach($rhp->permission_id);
                    }
                }
            }
            Role::find($id)->givePermissionTo($navigations);
            if(!empty($navigations)) {
                foreach($navigations as $permission_id){
                    RoleHasPermission::where('permission_id', $permission_id)->where('role_id', $id)
                                       ->update([
                                           'created_by' => auth()->user()->id,
                                           'created_at' => Carbon::now()->format('Y-m-d H:i:s')
                                        ]);
                }
            }

            $total = RoleHasPermission::where('role_id', $id)->get()->count();
        } else {
            $validator = Validator::make($request->all(), [
                'actions'   => ['array'],
            ]);

            if ($validator->fails()) {
                return json_encode(array('sts' => 'errors', 'errors' => $validator->errors()));
            }

            // if (!auth()->user()->hasRole('developer')) {
            //     $role = Role::findOrFail($id);
            //     if (!count(array_intersect(auth()->user()->getRoleNames()->toArray(), ["$role->name"]))) {
            //         $per = Permission::where('name', request()->segment(1))->first();
            //         $idpc = $per->children->first()->id;
            //         if (empty(count(array_intersect(request('actions'), ["$idpc"])))) {
            //             return json_encode(
            //                 array(
            //                     'sts' => 'warning',
            //                     'msg' => 'Gagal diperbaharui, '.ucwords(str_replace('-', ' ', $per->children->first()->name)).' tidak boleh dihapus.'
            //                 )
            //             );
            //         }
            //     }
            // }

            if (auth()->user()->hasRole('developer')) {
                $actions = request('actions');
            } else {
                $rhc = $this->nav($id, 'role-has-permission-create');
                $rhe = $this->nav($id, 'role-has-permission-edit');
                $uhc = $this->nav($id, 'user-has-permission-create');
                $uhe = $this->nav($id, 'user-has-permission-edit');
                if ($rhc+$rhe+$uhc+$uhe) {
                    if($rhc) { $ce[] = "$rhc"; }
                    if($rhe) { $ce[] = "$rhe"; }
                    if($uhc) { $ce[] = "$uhc"; }
                    if($uhe) { $ce[] = "$uhe"; }
                    if (empty(request('actions'))) {
                        $actions = $ce;
                    } else {
                        $actions = array_merge(request('actions'), $ce);
                    }
                } else {
                    $actions = request('actions');
                }
            }

            $rhps = RoleHasPermission::where('role_id', $id)->get();
            foreach($rhps as $rhp) {
                // detach
                if(empty($rhp->permission->navigation_id)) {
                    if(empty(count(array_intersect($actions ?? ["0"] , ["$rhp->permission_id"])))) {
                        Role::find($id)->permissions()->detach($rhp->permission_id);
                    }
                }
            }
            Role::find($id)->givePermissionTo($actions);
            if(!empty($actions)) {
                foreach($actions as $permission_id){
                    RoleHasPermission::where('permission_id', $permission_id)->where('role_id', $id)
                                       ->update([
                                           'created_by' => auth()->user()->id,
                                           'created_at' => Carbon::now()->format('Y-m-d H:i:s')
                                        ]);
                }
            }

            $total = 1;
        }

        return json_encode(
            array(
                'sts'   => 'success',
                'msg'   => 'Berhasil diperbaharui.',
                'scs'   => $total,
                'nav'   => Role::doesnthave('permissions')->whereNotIn('name', ['developer'])->get()->count()
            )
        );
    }

    //-------------------

    public function table()
    {
        $model = Role::select(['id', 'name', 'guard_name'])->has('permissions')->orderBy('name', 'asc')->get();
        if(auth()->user()->hasRole('developer')) {
            $dev = Role::select(['id', 'name', 'guard_name'])->where('name', 'developer')->orderBy('name', 'asc')->get();
            $model = $model->merge($dev)->sortBy('name');
        }

        return DataTables::of($model)
            ->addColumn('name', function ($model){
                return ucwords($model->name);
            })
            ->addColumn('navigations', function ($model){
                return $model->name == 'developer' ? 'Semua' : $model->getNavigations($model->getAllPermissions()->pluck('navigation_id')->filter());
            })
            ->addColumn('action', function ($model){
                return $model->name == 'developer' ? 'Semua' : $model->getActions($model->getAllPermissions()->pluck('parent_id', 'name')->filter());
            })
            ->addColumn('option', function ($model) {
                return view($this->folder().'_action', [
                    'model'         => $model,
                    'action'        => $this->countNav($model->id),
                    'url_show_nav'  => route($this->link().'show', [$model->id, 'nav']),
                    'url_show_act'  => route($this->link().'show', [$model->id, 'act'])
                ]);
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'time', 'date', 'navigations'])
            ->make(true);
    }
}
