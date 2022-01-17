<?php

namespace App\Http\Controllers\Master;

use App\Models\Unit;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;

class UnitController extends Controller
{
    private function validator($request, $id = false) {
        $validator = Validator::make($request->all(), [
            'name'      => ['required', 'unique:units,name,'.$id],
        ],  [
            'name.required' => 'Harus diisi.',
            'name.unique' => 'Sudah digunakan.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'sts' => 'errors',
                'errors' => $validator->errors()
            ]);
        }
    }

    public function index()
    {
        return view($this->folder().'index', [
            'urlTable'  => route($this->link().'table'),
            'urlCreate' => route($this->link().'create'),
            'canCreate' => $this->can('create'),
            'canEdit'   => $this->can('edit'),
            'canDelete' => $this->can('delete'),
        ]);
    }

    public function create()
    {
        return view($this->folder().'_form', [
            'column'    => new Unit,
            'url'       => route($this->link().'store'),
            'method'    => 'POST',
        ]);
    }

    public function store(Request $request)
    {
        if ($this->validator($request)) {
            return $this->validator($request);
        }

        $model              = new Unit;
        $model->name        = request('name');
        $model->created_by  = auth()->user()->id;
        $model->updated_by  = auth()->user()->id;
        $model->save();

        return response()->json([
            'sts' => 'store',
            'icon' => 'success',
            'msg' => 'Berhsil disimpan.'
        ]);
    }

    public function edit($id)
    {
        return view($this->folder().'_form', [
            'column'    => Unit::findOrFail($id),
            'url'       => route($this->link().'update', $id),
            'method'    => 'PUT',
        ]);
    }

    public function update(Request $request, $id)
    {
        if ($this->validator($request, $id)) {
            return $this->validator($request, $id);
        }

        $model              = Unit::findOrFail($id);
        $model->name        = request('name');
        $model->updated_by  = auth()->user()->id;
        $model->save();

        return response()->json([
            'sts' => 'update',
            'icon' => 'success',
            'msg' => 'Berhsil diperbaharui.'
        ]);
    }

    public function destroy($id)
    {
        $model = Unit::findOrFail($id);
        $model->deleted_by  = auth()->user()->id;
        $model->save();
        $model->delete();

        return response()->json([
            'icon' => 'success',
            'msg' => 'Berhsil dihapus.'
        ]);
    }

    //---------------------------------------------

    public function table()
    {
        $model = Unit::orderBy('name', 'asc')->get();
        return DataTables::of($model)
            ->addColumn('items', function ($model) {
                return $model->items->count();
            })
            ->addColumn('created_by', function ($model) {
                return $model->createdBy->name;
            })
            ->addColumn('updated_by', function ($model) {
                return $model->updatedBy->name;
            })
            ->addColumn('action', function ($model) {
                return view($this->folder().'_action', [
                    'msgDelete'         => $model->name,
                    'itemsWithTrashed'  => $model->itemsWithTrashed->count(),
                    'items'             => $model->items->count(),
                    'canEdit'           => $this->can('edit'),
                    'canDelete'         => $this->can('delete'),
                    'urlEdit'           => route($this->link().'edit', $model->id),
                    'urlDestroy'        => route($this->link().'destroy', $model->id)
                ]);
            })
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->make(true);
    }
}
