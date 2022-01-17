<?php

namespace App\Http\Controllers;

use App\Models\{Unit, Item, IncomingItem, OutgoingItem};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PDF;
use Yajra\Datatables\Datatables;

class ReportController extends Controller
{
    public function index()
    {
        return view($this->folder().'index', [
            'urlCreate' => route($this->link().'create'),
        ]);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), ['report' => ['required']], ['report.required' => 'Harus diisi.']);

        if ($validator->fails()) {
            return json_encode(array('sts' => 'errors', 'errors' => $validator->errors()));
        }

        return json_encode(
            array(
                'sts'       => 'ok',
                'urlPrint'  => route($this->link().'print', [
                    'report'    => request('report'),
                    'date_from' => request('date_from'),
                    'date_to'   => request('date_to')
                ])
            )
        );
    }

    public function print()
    {
        switch (request('report')) {
            case '1':
                $view = view($this->folder().'._item', [
                    'model'     => Item::with('type')
                                        ->orderBy('name', 'asc')
                                        ->get()
                                        ->sortBy('type.name'),
                    'date_from' => request('date_from'),
                    'date_to'   => request('date_to')
                ])->render();
                break;
            case '2':
                $view = view($this->folder().'._incoming_item', [
                    'model'     => IncomingItem::whereBetween('date', [$this->dateYmd(request('date_from')), $this->dateYmd(request('date_to'))])
                                    ->orderBy('date', 'desc')
                                    ->orderBy('code', 'desc')
                                    ->get(),
                    'date_from' => request('date_from'),
                    'date_to'   => request('date_to')
                ])->render();
                break;
            case '3':
                $view = view($this->folder().'._outgoing_item', [
                    'model'     => OutgoingItem::whereBetween('date', [$this->dateYmd(request('date_from')), $this->dateYmd(request('date_to'))])
                                    ->orderBy('date', 'desc')
                                    ->orderBy('code', 'desc')
                                    ->get(),
                    'date_from' => request('date_from'),
                    'date_to'   => request('date_to')
                ])->render();
                break;

            default:
                $html = 'Not Found';
                break;
        }
        $pdf = PDF::loadHtml($view)->setPaper('a4');
        return $pdf->stream('Laporan.pdf');
    }
}
