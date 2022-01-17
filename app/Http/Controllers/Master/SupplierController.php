<?php

namespace App\Http\Controllers\Master;

use App\Models\Supplier;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Validator, Storage};
use Yajra\Datatables\Datatables;

class SupplierController extends Controller
{
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
            'column'    => new Supplier,
            'url'       => route($this->link().'store'),
            'method'    => 'POST',
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'          => ['required'],
            'hp'            => ['required', 'unique:suppliers,hp'],
            'address'       => ['required'],
            'image_logo'    => ['nullable', 'mimes:png,jpg,jpeg']
        ],  [
            'name.required' => 'Harus diisi.',
            'hp.required' => 'Harus diisi.',
            'address.required' => 'Harus diisi.',
            'hp.unique' => 'Sudah digunakan.',
        ]);

        if ($validator->fails()) {
            return json_encode(array('sts' => 'errors', 'errors' => $validator->errors()));
        }

        if (request()->file('image_logo')) {
            $image = request()->file('image_logo')->store('supplier', 'public_image');
        } else {
            $image = NULL;
        }

        $model              = new Supplier;
        $model->name        = request('name');
        $model->hp          = request('hp');
        $model->address     = request('address');
        $model->image       = $image;
        $model->created_by  = auth()->user()->id;
        $model->updated_by  = auth()->user()->id;
        $model->save();

        return response()->json([
            'sts'   => 'store',
            'icon'  => 'success',
            'msg'   => 'Berhsil disimpan.'
        ]);
    }

    public function edit($id)
    {
        return view($this->folder().'_form', [
            'column'    => Supplier::findOrFail($id),
            'url'       => route($this->link().'update', $id),
            'method'    => 'PUT',
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'      => ['required'],
            'hp'        => ['required', 'unique:suppliers,hp,'.$id],
            'address'   => ['required'],
            'image'     => ['nullable', 'mimes:png,jpg,jpeg']
        ],  [
            'name.required' => 'Harus diisi.',
            'hp.required' => 'Harus diisi.',
            'address.required' => 'Harus diisi.',
            'hp.unique' => 'Sudah digunakan.',
        ]);

        if ($validator->fails()) {
            return json_encode(array('sts' => 'errors', 'errors' => $validator->errors()));
        }

        if ($request->hasFile('image')) {
            Storage::disk('public_image')->delete(Supplier::where('user_id', auth()->user()->id)->first()->image);
            $image = request()->file('image_profile')->store('supplier', 'public_image');
        } else {
            $image = NULL;
        }

        $model              = Supplier::findOrFail($id);
        $model->name        = request('name');
        $model->hp          = request('hp');
        $model->address     = request('address');
        $model->created_by  = auth()->user()->id;
        $model->updated_by  = auth()->user()->id;
        $model->save();

        return json_encode(array('sts' => 'update', 'icon' => 'success', 'msg' => 'Berhasil diperbaharui.'));
    }

    public function destroy($id)
    {
        $model = Supplier::findOrFail($id);
        $model->deleted_by  = auth()->user()->id;
        $model->save();
        $model->delete();

        foreach ($model->incomingItems as $ii) {
            $mii = IncomingItem::find($ii->id);
            $mii->deleted_by  = auth()->user()->id;
            $mii->save();
            $mii->delete();
        }

        return json_encode(array('icon' => 'success', 'msg' => 'Berhsil dihapus.'));
    }

    //---------------------------------------------

    public function table()
    {
        $model = Supplier::orderBy('name', 'asc')->get();
        return DataTables::of($model)
            ->addColumn('action', function ($model) {
                $msg = $model->incomingItems->count() ? $model->name.', <span class="text-danger">akan menghapus juga barang masuk dan barang keluar</span>' : $model->name;
                return view($this->folder().'_action', [
                    'trasaction'    => $model->incomingItems->count(),
                    'msgDelete'     => $msg,
                    'canEdit'       => $this->can('edit'),
                    'canDelete'     => $this->can('delete'),
                    'urlEdit'       => route($this->link().'edit', $model->id),
                    'urlDestroy'    => route($this->link().'destroy', $model->id)
                ]);
            })
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->make(true);
    }
}
