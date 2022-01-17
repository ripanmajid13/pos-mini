<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Yajra\Datatables\Datatables;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->link = request()->segment(1);
        $this->folder = "pages.setting.";
    }

    public function index()
    {
        return view($this->folder.'index', [
            'urlTable'  => route($this->link.'.table')
        ]);
    }

    public function edit($id)
    {
        return view($this->folder.'_form', [
            'column' => Setting::findOrFail($id),
            'action' => route($this->link.'.update', $id)
        ]);
    }

    public function update(Request $request, $id)
    {
        if ($request->file('description')) {
            $validator = Validator::make($request->all(), [
                'description'   => ['required', 'mimes:png,jpg,jpeg', 'dimensions:max_width=518,max_height=340'],
            ],[
                'description.required' => 'Harus diisi.'
            ]);

            if ($validator->fails()) {
                return json_encode(array('sts' => 'errors', 'errors' => $validator->errors()));
            }

            if(request('description')) {
                // Storage::delete(Setting::find($id)->description);
                Storage::disk('public_image')->delete(Setting::find($id)->description);
                $description = request()->file('description')->store('setting', 'public_image');
            }

            $model              = Setting::findOrFail($id);
            $model->description = $description;
            $model->updated_by  = auth()->user()->id;
            $model->save();

            return json_encode(array('sts' => 'update', 'icon' => 'success', 'msg' => 'Berhasil diperbaharui.'));
        } else {
            $validator = Validator::make($request->all(), [
                'description'   => ['required'],
            ],[
                'description.required' => 'Harus diisi.'
            ]);

            if ($validator->fails()) {
                return json_encode(array('sts' => 'errors', 'errors' => $validator->errors()));
            }

            $model              = Setting::findOrFail($id);
            $model->description = request('description');
            $model->updated_by  = auth()->user()->id;
            $model->save();

            return json_encode(array('sts' => 'update', 'icon' => 'success', 'msg' => 'Berhasil diperbaharui.'));
        }
    }

    //-------------------

    public function table()
    {
        $model = Setting::orderBy('name', 'asc')->get();
        return DataTables::of($model)
            ->addColumn('description', function ($model){
                return $model->description ? str_replace('setting/', '', $model->description) : 'Default';
            })
            ->addColumn('updateby', function ($model){
                return $model->updatedBy->name;
            })
            ->addColumn('action', function ($model) {
                return view($this->folder.'_action', [
            //          'model'         => $model,
            //          'canEdit'       => $this->canEdit,
            //          'canDelete'     => $this->canDelete,
                    'urlEdit'       => route($this->link.'.edit', $model->id),
            //          'urlDestroy'    => route($this->link.'.destroy', $model->id)
                ]);
            })
            ->addIndexColumn()
            ->rawColumns([])
            ->make(true);
    }
}
