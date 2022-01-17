<?php

namespace App\Http\Controllers\Privileges;

use App\Models\Navigation;
use App\Models\Permission;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;

class NavigationController extends Controller
{
    private function getTable()
    {
        $data = array();
        $parents = Navigation::select(['id', 'parent_id', 'name', 'folder', 'url', 'icon', 'position'])->with('children')->whereNull('parent_id')->orderBy('position', 'asc')->get();
        foreach ($parents as $parent) {
            $isLink = $parent->url ? 1 : 0;
            $a = $parent->url ? $parent->permissions->first()->children->count() : '';
            $b = $a ? 'index, '.implode(", ", str_replace($parent->url.'-', '', $parent->permissions->first()->children->pluck('name')->toArray())) : 'index';
            $data[] = array (
                'id'        => $parent->id,
                'parent_id' => $parent->parent_id,
                'name'      => $parent->name,
                'folder'    => $isLink ? ($parent->folder ? $parent->folder.'.'.$parent->url : $parent->url) : '',
                'url'       => $parent->url,
                'icon'      => $parent->icon,
                'position'  => $parent->position,
                'child'     => $parent->children->count(),
                'action'    => $isLink ? $b : '',
                'edit'      => 'btn-edit-parent',
                'data'      => json_encode([
                    'child'     => $parent->children->count(),
                    'name'      => $parent->name,
                    'position'  => $parent->position,
                    'icon'      => $parent->icon,
                    'link'      => $parent->url,
                    'folder'    => $isLink ? ($parent->folder ? $parent->folder : ' ') : ''
                ])
            );
            foreach ($parent->children as $child) {
                $ac = $child->permissions->first()->children->count();
                $data[] = array (
                    'id'        => $child->id,
                    'parent_id' => $child->parent_id,
                    'name'      => $child->name,
                    'folder'    => $child->folder.'.'.strtolower(str_replace(' ', '-', $parent->name)).'.'.$child->url,
                    'url'       => $child->url,
                    'icon'      => $child->icon,
                    'position'  => $child->position,
                    'child'     => 1,
                    'action'    => $ac ? 'index, '.implode(", ", str_replace($child->url.'-', '', $child->permissions->first()->children->pluck('name')->toArray())) : 'index',
                    'edit'      => 'btn-edit-child',
                    'data'      => json_encode([
                        'parent'    => $child->parent_id,
                        'name'      => $child->name,
                        'position'  => $child->position,
                        'folder'    => $child->folder
                    ])
                );
            }
        }
        return $data;
    }

    private function getTableAction($id)
    {
        return Navigation::findOrFail($id)->permissions->first()->children;
    }

    private function getParent()
    {
        $html = '<option value=""></option>';
        $navigations = Navigation::select(['id', 'name'])->whereNull('parent_id')->whereNull('url')->orderBy('position', 'asc')->get();
        foreach($navigations as $navigation) {
            $html .= '<option value="'.$navigation->id.'">'.$navigation->name.'</option>';
        }

        return $html;
    }

    public function __construct()
    {
        $this->link = request()->segment(1);
        $this->folder = "pages.privileges.navigation.";
    }

    public function index()
    {
        return view($this->folder.'index', [
            'urlTable'  => route($this->link.'.table'),
            'urlForm'   => route($this->link.'.store'),
            'parent'    => $this->getParent()
        ]);
    }

    public function store(Request $request)
    {
        if (request('form') == 'parent') {
            $validator = Validator::make($request->all(), [
                'name_parent'       => ['required'],
                'position_parent'   => ['required', 'numeric'],
                'icon'              => ['required'],
                'is_link'           => ['required'],
            ], [
                'name_parent.required'      => 'Harus diisi.',
                'position_parent.required'  => 'Harus diisi.',
                'icon.required'             => 'Harus diisi.',

                'position_parent.numeric'   => 'Harus berupa angka.'
            ]);

            if ($validator->fails()) {
                return json_encode(array('sts' => 'errors', 'errors' => $validator->errors()));
            }

            $cp = Navigation::whereNull('parent_id')->where('position', request('position_parent'))->get()->count();
            if ($cp) {
                return json_encode(array('sts' => 'store', 'icon' => 'warning', 'msg' => 'Position parent '.request('position_parent').' sudah ada.'));
            } else {
                $model              = new Navigation;
                $model->parent_id   = NULL;
                $model->name        = request('name_parent');
                $model->folder      = strtolower(str_replace([' ', '/', '-', ','], '.', request('folder_parent')));
                $model->url         = request('is_link') == 'y' ? str_replace([" ", "_", "-"], "-", strtolower(request('name_parent'))) : NULL;
                $model->icon        = request('icon');
                $model->position    = request('position_parent');
                $model->save();

                if (request('is_link') == 'y') {
                    $permission                 = new Permission;
                    $permission->navigation_id  = $model->id;
                    $permission->parent_id      = NULL;
                    $permission->name           = $model->url;
                    $permission->guard_name     = 'web';
                    $permission->save();
                }
            }

            return json_encode(
                array(
                    'sts' => 'store',
                    'icon' => 'success',
                    'msg' => "Parent berhasil disimpan.",
                    'parent' => $this->getParent()
                )
            );
        } else {
            $validator = Validator::make($request->all(), [
                'parent_id'         => ['required'],
                'name_child'        => ['required'],
                'position_child'    => ['required', 'numeric'],
            ], [
                'parent_id.required'       => 'Harus dipilih.',
                'name_child.required'      => 'Harus diisi.',
                'position_child.required'  => 'Harus diisi.',

                'position_child.numeric'   => 'Harus berupa angka.'
            ]);

            if ($validator->fails()) {
                return json_encode(array('sts' => 'errors', 'errors' => $validator->errors()));
            }

            $checkPosition = Navigation::where('parent_id', request('parent_id'))->where('position', request('position_child'))->get()->count();
            if ($checkPosition) {
                return json_encode(array('sts' => 'store', 'icon' => 'warning', 'msg' => 'Position child '.request('position_child').' sudah ada.'));
            }

            $model              = new Navigation;
            $model->parent_id   = request('parent_id');
            $model->name        = request('name_child');
            $model->folder      = strtolower(str_replace([' ', '/', '-', ','], '.', request('folder_child')));
            $model->url         = str_replace([" ", "_", "-"], "-", strtolower(request('name_child')));
            $model->icon        = NULL;
            $model->position    = request('position_child');
            $model->save();

            $permission                 = new Permission;
            $permission->navigation_id  = $model->id;
            $permission->parent_id      = NULL;
            $permission->name           = $model->url;
            $permission->guard_name     = 'web';
            $permission->save();

            return json_encode(array('sts' => 'store', 'icon' => 'success', 'msg' => "Child berhasil disimpan."));
        }
    }

    public function show($id)
    {
        $html = view($this->folder.'_detail', [
            'column'    => Navigation::findOrFail($id),
            'action'    => route($this->link.'.store.action', $id)
        ])->render();

        return json_encode(array('html' => $html, 'table' => route($this->link.'.table.action', $id)));
    }

    public function update(Request $request, $id)
    {
        if (request('form') == 'parent') {
            $validator = Validator::make($request->all(), [
                'name_parent'       => ['required'],
                'position_parent'   => ['required', 'numeric'],
                'icon'              => ['required'],
                'is_link'           => ['required'],
            ], [
                'name_parent.required'      => 'Harus diisi.',
                'position_parent.required'  => 'Harus diisi.',
                'icon.required'             => 'Harus diisi.',

                'position_parent.numeric'   => 'Harus berupa angka.'
            ]);

            if ($validator->fails()) {
                return json_encode(array('sts' => 'errors', 'errors' => $validator->errors()));
            }

            $cp = Navigation::whereNotIn('id', [$id])->whereNull('parent_id')->where('position', request('position_parent'))->get()->count();
            if ($cp) {
                return json_encode(array('sts' => 'update', 'icon' => 'warning', 'msg' => 'Position parent '.request('position_parent').' sudah ada.'));
            } else {
                $model              = Navigation::findOrFail($id);
                $model->parent_id   = NULL;
                $model->name        = request('name_parent');
                $model->folder      =  request('is_link') == 'y' ? strtolower(str_replace([' ', '/', '-', ','], '.', request('folder_parent'))) : NULL;
                if (empty($model->children()->count())) {
                    $model->url     = request('is_link') == 'y' ? str_replace([" ", "_", "-"], "-", strtolower(request('name_parent'))) : NULL;
                }
                $model->icon        = request('icon');
                $model->position    = request('position_parent');
                $model->save();

                $cp = Permission::where('navigation_id', $id)->get()->count();
                if (request('is_link') == 'y') {
                    if (!$cp) {
                        $permission                 = new Permission;
                        $permission->navigation_id  = $model->id;
                        $permission->parent_id      = NULL;
                        $permission->name           = $model->url;
                        $permission->guard_name     = 'web';
                        $permission->save();
                    }
                } elseif (request('is_link') == 'n') {
                    if ($cp) {
                        $permission_n = Permission::findOrFail($cp->first()->id);
                        $permission_n->forceDelete();
                    }
                }
            }

            return json_encode(
                array(
                    'sts' => 'update',
                    'icon' => 'success',
                    'msg' => "Parent berhasil diperbaharui.",
                    'parent' => $this->getParent()
                )
            );
        } else {
            $validator = Validator::make($request->all(), [
                'parent_id'         => ['required'],
                'name_child'        => ['required'],
                'position_child'    => ['required', 'numeric'],
            ], [
                'parent_id.required'       => 'Harus dipilih.',
                'name_child.required'      => 'Harus diisi.',
                'position_child.required'  => 'Harus diisi.',

                'position_child.numeric'   => 'Harus berupa angka.'
            ]);

            if ($validator->fails()) {
                return json_encode(array('sts' => 'errors', 'errors' => $validator->errors()));
            }

            $checkPosition = Navigation::whereNotIn('id', [$id])->where('parent_id', request('parent_id'))->where('position', request('position_child'))->get()->count();
            if ($checkPosition) {
                return json_encode(array('sts' => 'update', 'icon' => 'warning', 'msg' => 'Position child '.request('position_child').' sudah ada.'));
            }

            $model              = Navigation::findOrFail($id);
            $model->parent_id   = request('parent_id');
            $model->name        = request('name_child');
            $model->folder      = strtolower(str_replace([' ', '/', '-', ','], '.', request('folder_child')));
            $model->url         = str_replace([" ", "_", "-"], "-", strtolower(request('name_child')));
            $model->icon        = NULL;
            $model->position    = request('position_child');
            $model->save();

            $permission        = Permission::find(Permission::where('navigation_id', $id)->first()->id);
            $permission->name  = $model->url;
            $permission->save();

            return json_encode(array('sts' => 'update', 'icon' => 'success', 'msg' => "Child berhasil diperbaharui."));
        }
    }

    public function destroy($id)
    {
        $model = Navigation::findOrFail($id);

        if ($model->children->count()) {
            return json_encode(array('icon' => 'warning', 'msg' => 'Navigation masih punya child.'));
        }

        if ($model->url) {
            if ($model->permissions->first()->roleHasPermission->count()) {
                return json_encode(array('icon' => 'warning', 'msg' => 'Navigation sedang digunakan.'));
            }
        }

        $model->forceDelete();
        if($model->url) {
            $permission = Permission::find(Permission::where('navigation_id', $id)->first()->id);
            $permission->forceDelete();
        }

        return json_encode(array('icon' => 'success', 'msg' => 'Berhsil dihapus.'));
    }

    //---------------------------------------------

    public function table()
    {
        return DataTables::of($this->getTable())
            ->addColumn('position', function ($model) {
                return empty($model['parent_id']) ? $model['position'] : '<span class="float-right">'.$model['position'].'</span>';
            })
            ->addColumn('name', function ($model) {
                if ($model['url']) {
                    return $model['name'];
                } else {
                    return $model['child'] ? $model['name'] : '<span class="text-danger">'.$model['name'].'</span>';
                }
            })
            ->addColumn('icon', function ($model) {
                return '<i class="'.$model['icon'].'"></i>';
            })
            ->addColumn('option', function ($model) {
                return view($this->folder.'_action', [
                    'model'     => $model,
                    'data'      => $model['data'],
                    'urlDetail' => route($this->link.'.show', $model['id']),
                    'urlEdit'   => route($this->link.'.update', $model['id']),
                    'urlDelete' => route($this->link.'.destroy', $model['id'])
                ]);
            })
            ->addIndexColumn()
            ->rawColumns(['position', 'name', 'icon', 'url'])
            ->make(true);
    }

    public function tableAction($id)
    {
        return DataTables::of($this->getTableAction($id))
            ->addColumn('name', function ($model) use($id) {
                $nav = Navigation::find($id);
                return ucwords(str_replace($nav->url.'-', '', $model->name));
            })
            ->addColumn('option', function ($model) use($id) {
                $nav = Navigation::find($id);
                return '<div class="text-center"><a href="'.route($this->link.'.destroy.action', $model->id).'" data-text="'.str_replace($nav->url.'-', ' ', $model->name).'" class="btn btn-danger btn-delete-action btn-xs py-0 px-1" title="Delete">'.
                    '<i class="fas fa-trash"></i>'.
                '</a></div>';
            })
            ->addIndexColumn()
            ->rawColumns(['option'])
            ->make(true);
    }

    //---------------------------------------------

    public function storeAction(Request $request, $id)
    {
        $column = Navigation::find($id);
        $validator = Validator::make($request->all(), [
            'name'       => ['required'],
        ], [
            'name.required'      => 'Harus diisi.',

        ]);

        if ($validator->fails()) {
            return json_encode(array('sts' => 'errors', 'errors' => $validator->errors()));
        }

        $permission = Permission::where('parent_id', $column->permissions->first()->id)
                                  ->where('name', $column->url.'-'.str_replace([" ", "_", "-"], "-", strtolower(request('name'))))
                                  ->get()->count();

        if ($permission) {
            return json_encode(array('sts' => 'warning', 'msg' => 'Action sudah terdaftar.'));
        }

        $model                 = new Permission;
        $model->navigation_id  = NULL;
        $model->parent_id      = $column->permissions->first()->id;
        $model->name           = $column->url.'-'.str_replace([" ", "_", "-"], "-", strtolower(request('name')));
        $model->guard_name     = 'web';
        $model->save();

        return json_encode(array('sts' => 'success', 'msg' => 'Berhasil disimpan.'));
    }

    public function destroyAction($id)
    {
        $model = Permission::findOrFail($id);

        if ($model->roleHasPermission->count()) {
            return json_encode(array('icon' => 'warning', 'msg' => 'Action sedang digunakan.'));
        }

        $model->forceDelete();
        return json_encode(array('icon' => 'success', 'msg' => 'Berhsil dihapus.'));
    }

}
