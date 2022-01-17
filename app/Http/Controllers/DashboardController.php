<?php

namespace App\Http\Controllers;

use App\Models\{Item, IncomingItem, OutgoingItem, Supplier, User, Type};
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private function ii()
    {
        $data = array();
        $m = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        foreach ($m as $key) {
            $data[] = IncomingItem::whereMonth('date', $key)->get()->count();
        }
        return $data;
    }

    private function oi()
    {
        $data = array();
        $m = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        foreach ($m as $key) {
            $data[] = OutgoingItem::whereMonth('date', $key)->get()->count();
        }
        return $data;
    }

    public function index()
    {
        $itemMinimum = array();
        $ims = Item::with('type')->orderBy('name', 'asc')->get()->sortBy('type.name');
        foreach ($ims as $im) {
            if ($im->stock($im->id) <= 10) {
                $itemMinimum[] = [
                    'name'  => $im->type->name.' '.$im->name,
                    'stock' => $im->stock($im->id).' '.$im->unit->name
                ];
            }
        }

        return view('pages.dashboard',[
            'item'          => Item::get()->count(),
            'supplier'      => Supplier::get()->count(),
            'user'          => User::with('roles')
                                ->whereHas('roles' , function ($query) {
                                    $query->whereNotIn('name', ['developer']);
                                })->get()->count(),
            'type'          => Type::get()->count(),
            'ii'            => json_encode($this->ii()),
            'oi'            => json_encode($this->oi()),
            'itemMinimum'   => $itemMinimum,
            'incomingItem'  => IncomingItem::orderBy('created_at', 'desc')->take(5)->get(),
            'outgoingItem'  => OutgoingItem::orderBy('created_at', 'desc')->take(5)->get()
        ]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
