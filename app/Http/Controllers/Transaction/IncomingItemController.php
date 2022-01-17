<?php

namespace App\Http\Controllers\Transaction;

use App\Models\{Item, IncomingItem, Supplier};
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;

class IncomingItemController extends Controller
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
            'column'    => new IncomingItem,
            'url'       => route($this->link().'store'),
            'items'     => Item::orderBy('name', 'asc')->get(),
            'suppliers' => Supplier::orderBy('name', 'asc')->get(),
            'method'    => 'POST',
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date'          => ['required'],
            'item_id'       => ['required'],
            'supplier_id'   => ['required'],
            'qty'           => ['required'],
        ],  [
            'date.required'         => 'Harus diisi.',
            'item_id.required'      => 'Harus diisi.',
            'supplier_id.required'  => 'Harus diisi.',
            'qty.required'          => 'Harus diisi.',
        ]);

        if ($validator->fails()) {
            return json_encode(array('sts' => 'errors', 'errors' => $validator->errors()));
        }

        $model              = new IncomingItem;
        $model->code        = $this->code('TBM', request('date'));
        $model->date        = $this->dateYmd(request('date'));
        $model->item_id     = request('item_id');
        $model->supplier_id = request('supplier_id');
        $model->qty         = request('qty');
        $model->created_by  = auth()->user()->id;
        $model->updated_by  = auth()->user()->id;
        $model->save();

        return json_encode(array('sts' => 'store', 'icon' => 'success', 'msg' => 'Berhsil disimpan.'));
    }

    public function edit($id)
    {
        return view($this->folder().'_form', [
            'column'    => IncomingItem::findOrFail($id),
            'url'       => route($this->link().'update', $id),
            'method'    => 'PUT',
            'items'     => Item::orderBy('name', 'asc')->get(),
            'suppliers' => Supplier::orderBy('name', 'asc')->get(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'date'          => ['required'],
            'item_id'       => ['required'],
            'supplier_id'   => ['required'],
            'qty'           => ['required'],
        ],  [
            'date.required'         => 'Harus diisi.',
            'item_id.required'      => 'Harus diisi.',
            'supplier_id.required'  => 'Harus diisi.',
            'qty.required'          => 'Harus diisi.',
        ]);

        if ($validator->fails()) {
            return json_encode(array('sts' => 'errors', 'errors' => $validator->errors()));
        }

        $model              = IncomingItem::findOrFail($id);
        $model->code        = $this->code('TBM', request('date'));
        $model->date        = $this->dateYmd(request('date'));
        $model->item_id     = request('item_id');
        $model->supplier_id = request('supplier_id');
        $model->qty         = request('qty');
        $model->updated_by  = auth()->user()->id;
        $model->save();


        return json_encode(array('sts' => 'update', 'icon' => 'success', 'msg' => 'Berhasil diperbaharui.'));
    }

    public function destroy($id)
    {
        $model = IncomingItem::findOrFail($id);
        $model->deleted_by  = auth()->user()->id;
        $model->save();
        $model->delete();

        return json_encode(array('icon' => 'success', 'msg' => 'Berhsil dihapus.'));
    }

    //---------------------------------------------

    public function table()
    {
        $model = IncomingItem::orderBy('created_at', 'desc')->get();
        return DataTables::of($model)
            ->addColumn('date', function ($model) {
                return Carbon::parse($model->date)->format('d/m/Y');
            })
            ->addColumn('item_id', function ($model) {
                return $model->item->type->name.' '.$model->item->name;
            })
            ->addColumn('supplier_id', function ($model) {
                return $model->supplier->name;
            })
            ->addColumn('qty', function ($model) {
                return $model->qty.' '.$model->item->unit->name;
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
            ->rawColumns(['action'])
            ->make(true);
    }
}
