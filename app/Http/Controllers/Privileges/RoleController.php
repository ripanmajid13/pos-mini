<?php

namespace App\Http\Controllers\Privileges;

use App\Models\{Role, User};
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;

class RoleController extends Controller
{
    public function index()
    {
        return view($this->folder().'index', [
            'urlTable'  => route($this->link().'table'),
            'canCreate' => $this->can('create'),
            'canEdit'   => $this->can('edit'),
            'canDelete' => $this->can('delete'),
            'urlCreate' => route($this->link().'create')
        ]);
    }

    public function create()
    {
        return view($this->folder().'_form', [
            'column'    => new Role,
            'url'       => route($this->link().'store'),
            'method'    => 'POST'
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'unique:roles,name']
        ],[
            'name.required' => 'Harus diisi.'
        ]);

        if ($validator->fails()) {
            return json_encode(array('sts' => 'errors', 'errors' => $validator->errors()));
        }

        $model              = new Role;
        $model->name        = strtolower(request('name'));
        $model->guard_name  = request('guard_name') ?? 'web';
        $model->created_by  = auth()->user()->id;
        $model->updated_by  = auth()->user()->id;
        $model->save();

        return json_encode(array('sts' => 'store', 'icon' => 'success', 'msg' => 'Berhsil disimpan.'));
    }

    public function edit($id)
    {
        return view($this->folder().'_form', [
            'column'    => Role::findOrFail($id),
            'url'       => route($this->link().'update', $id),
            'method'    => 'PUT'
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'unique:roles,name,'.$id]
        ],[
            'name.required' => 'Harus diisi.'
        ]);

        if ($validator->fails()) {
            return json_encode(array('sts' => 'errors', 'errors' => $validator->errors()));
        }

        $model              = Role::findOrFail($id);
        $model->name        = strtolower(request('name'));
        $model->guard_name  = request('guard_name') ?? 'web';
        $model->updated_by  = auth()->user()->id;
        $model->save();

        return json_encode(array('sts' => 'update', 'icon' => 'success', 'msg' => 'Berhasil diperbaharui.'));
    }

    public function destroy($id)
    {
        $model  = Role::findOrFail($id);
        $users  = User::with('roles')
                        ->whereHas('roles' , function ($query) use($model)  {
                            $query->where('name',  $model->name);
                        })
                        ->get()->count();
        if ($users) {
            return json_encode(array('icon' => 'warning', 'msg' => 'Role '.$model->name.' sedang digunakan.'));
        } else {
            $model->deleted_by  = auth()->user()->id;
            $model->save();
            $model->delete();
        }

        return json_encode(array('icon' => 'success', 'msg' => 'Berhsil dihapus.'));
    }

    //-------------------

    public function table()
    {
        if(auth()->user()->hasRole('developer')) {
            $model = Role::select(['id', 'name', 'guard_name', 'created_by', 'updated_by'])->orderBy('name', 'asc')->get();
        } else {
            $model = Role::select(['id', 'name', 'guard_name', 'created_by', 'updated_by'])->whereNotIn('name', ['developer'])->orderBy('name', 'asc')->get();
        }
        return DataTables::of($model)
            ->addColumn('name', function ($model){
                return ucwords($model->name);
            })
            ->addColumn('created_by', function ($model){
                return $model->createdBy->name;
            })
            ->addColumn('updated_by', function ($model){
                return $model->updatedBy->name;
            })
            ->addColumn('action', function ($model) {
                return view($this->folder().'_action', [
                    'model'         => $model,
                    'canEdit'       => $this->can('edit'),
                    'canDelete'     => $this->can('delete'),
                    'urlEdit'       => route($this->link().'edit', $model->id),
                    'urlDestroy'    => route($this->link().'destroy', $model->id)
                ]);
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'time', 'date'])
            ->make(true);
    }
}
