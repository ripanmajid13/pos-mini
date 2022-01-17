<?php

namespace App\Http\Controllers\Privileges;

use App\Http\Controllers\Controller;
use App\Models\{User, UserHasRole};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Hash, Validator};
use Yajra\Datatables\Datatables;

class GuestController extends Controller
{
    public function index()
    {
        return view($this->folder().'index', [
            'urlTable'  => route($this->link().'table'),
            'urlCreate' => route($this->link().'create'),
            'canCreate' => $this->can('create'),
            'canEdit'   => $this->can('edit'),
            'canDelete' => $this->can('delete'),
            'canRoles'  => $this->can('roles'),
        ]);
    }

    public function create()
    {
        return view($this->folder().'_form', [
            'column'    => new User,
            'url'       => route($this->link().'store'),
            'method'    => 'POST',
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => ['required'],
            'username'  => ['nullable', 'string', 'unique:users,username'],
            'email'     => ['required', 'email:rfc,dns', 'unique:users,email'],
            'password'  => ['required', 'string', 'min:8'],
        ],  [
            'name.required' => 'Harus diisi.',
            'email.required' => 'Harus diisi.',
            'password.required' => 'Harus diisi.',
            'email.email'   => 'Harus berupa email.'
        ]);

        if ($validator->fails()) {
            return json_encode(array('sts' => 'errors', 'errors' => $validator->errors()));
        }

        $model              = new User;
        $model->name        = request('name');
        $model->username    = request('username');
        $model->email       = request('email');
        $model->password    = Hash::make(request('password'));
        $model->created_by  = auth()->user()->id;
        $model->updated_by  = auth()->user()->id;
        $model->save();

        return json_encode(array('sts' => 'store', 'icon' => 'success', 'msg' => 'Berhsil disimpan.'));
    }

    public function edit($id)
    {
        return view($this->folder().'_form', [
            'column'    => User::findOrFail($id),
            'url'       => route($this->link().'update', [$id, 0]),
            'method'    => 'PUT',
        ]);
    }

    public function update(Request $request, $id, $slug)
    {
        if ($slug) {
            $validator = Validator::make($request->all(), [
                'name'      => ['required'],
                'username'  => ['nullable', 'string', 'unique:users,username,'.$id],
                'email'     => ['required', 'email:rfc,dns', 'unique:users,email,'.$id],
                'password'  => ['nullable', 'string', 'min:8'],
                'roles'     => ['nullable', 'required', 'array'],
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'name'      => ['required'],
                'username'  => ['nullable', 'string', 'unique:users,username,'.$id],
                'email'     => ['required', 'email:rfc,dns', 'unique:users,email,'.$id],
                'password'  => ['nullable', 'string', 'min:8'],
                'roles'     => ['nullable', 'array'],
            ]);
        }

        if ($validator->fails()) {
            return json_encode(array('sts' => 'errors', 'errors' => $validator->errors()));
        }

        $model              = User::findOrFail($id);
        $model->name        = request('name');
        $model->username    = request('username');
        $model->email       = request('email');
        if(!empty(request('password'))){
            $model->password    = Hash::make(request('password'));
        }
        $model->updated_by  = auth()->user()->id;
        $model->save();
        if(!empty(request('roles'))){
            $model->syncRoles(request('roles'));
            foreach(request('roles') as $role_id) {
                UserHasRole::where('role_id', $role_id)->where('model_id', $id)
                                    ->update([
                                        'created_by' => auth()->user()->id,
                                        'created_at' => Carbon::now()->format('Y-m-d H:i:s')
                                    ]);
            }
        }

        return json_encode(array('sts' => 'update', 'icon' => 'success', 'msg' => 'Berhasil diperbaharui.'));
    }

    public function destroy($id)
    {
        $model = User::findOrFail($id);
        $model->deleted_by  = auth()->user()->id;
        $model->save();
        $model->delete();

        return json_encode(array('icon' => 'success', 'msg' => 'Berhsil dihapus.'));
    }

    //---------------------------------------------

    public function table()
    {
        $model = User::select(['id', 'name', 'username', 'email'])->doesntHave('roles')->orderBy('name', 'asc')->get();
        return DataTables::of($model)
            ->addColumn('action', function ($model) {
                return view($this->folder().'_action', [
                    'text'          => $model->name,

                    'canRoles'      => $this->can('roles'),
                    'canEdit'       => $this->can('edit'),
                    'canDelete'     => $this->can('delete'),

                    'urlRoles'      => route($this->link().'roles', $model->id),
                    'urlEdit'       => route($this->link().'edit', $model->id),
                    'urlDestroy'    => route($this->link().'destroy', $model->id)
                ]);
            })
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->make(true);
    }

    //---------------------------------------------

    public function roles($id)
    {
        return view($this->folder().'_roles', [
            'column'    => User::findOrFail($id),
            'url'       => route($this->link().'update', [$id, 1]),
            'method'    => 'PUT',
            'roles'     => $this->getRoles()
        ]);
    }
}
