<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Laporan Barang Masuk</title>
        <style>
            body{
                font-size: 11px;
                font-family: Calibri, Helvetica, sans-serif;
            }
            table {
                border-collapse: collapse;
                font-family: Calibri, Helvetica, sans-serif;
                width: 100%;
            }
            thead tr td {
                font-weight: bold;
            }
            td {
                border: 1px solid #000;
                padding: 3px 4px;
            }
            .text-center {
                text-align: center;
            }
        </style>
    </head>

    <body>
        <p class="text-center" style="font-size: 15px; margin-bottom: -10px;"><b>LAPORAN BARANG MASUK</b></p>
        <p class="text-center">Tanggal : {{ $date_from }} - {{ $date_to }}</p>

        <table class="table" page-break-inside: always;>
            <thead>
                <tr>
                    <td class="text-center" style="">NO</td>
                    <td class="text-center" style="">TANGGAL</td>
                    <td class="text-center" style="">NO TRANSAKSI</td>
                    <td class="text-center" style="">BARANG</td>
                    <td class="text-center" style="">SUPPLIER</td>
                    <td class="text-center" style="">JUMLAH</td>
                </tr>
            </thead>

            <tbody>
                @foreach ($model as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index+1 }}</td>
                        <td>{{ date_format(date_create($item->date), 'd/m/Y') }}</td>
                        <td>{{ $item->code }}</td>
                        <td>{{ $item->item->name }}</td>
                        <td>{{ $item->supplier->name }}</td>
                        <td>{{ $item->qty }} {{ $item->item->unit->name }}</td>
                    </tr>
                @endforeach
            </tbody>

            {{-- <tbody>
                @foreach ($subTitle as $index => $sb)
                    <tr>
                        <td class="text-center"><b>{{ $sb->conRoman($index+1) }}</b></td>
                        <td colspan="6"><b>{{ $sb->titleHistory($sb->id, $sb->title) }}</b></td>
                    </tr>

                    @foreach ($sb->items as $index2 => $item)
                        <tr>
                            <td class="text-center align-top">{{ $index2+1 }}</td>
                            <td class="align-top">
                                {{ $item->itemHistory($item->id, 'name', $item->name) }} <br />
                                @if ($item->itemHistory($item->id, 'specification', $item->specification) !== null)
                                    @php $specifications = explode("<li>", $item->itemHistory($item->id, 'specification', $item->specification)); $first = true; @endphp
                                    @foreach ($specifications as $rowS)
                                        @if ($first)
                                            @php $first = false; @endphp
                                        @else
                                            {{ str_replace("</li>", "", $rowS) }} <br />
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                            <td class="text-right align-top">
                                {{ $item->itemHistory($item->id, 'quantity', $item->quantity) }}
                            </td>
                            <td class="align-top">
                                {{ $item->itemHistory($item->id, 'uom', $item->uom) }}
                            </td>
                            <td class="text-right align-top">
                                {{ number_format($item->itemHistory($item->id, 'price', $item->price), 0, ',', '.') }}
                            </td>
                            <td class="text-right align-top">
                                @php
                                    $total = $item->itemHistory($item->id, 'price', $item->price)*$item->itemHistory($item->id, 'quantity', $item->quantity);
                                @endphp
                                {{ number_format($total, 0, ',', '.') }}
                            </td>
                            <td class="align-top">
                                {{ $item->itemHistory($item->id, 'description', $item->description) }}
                            </td>
                        </tr>
                    @endforeach
                @endforeach

                <tr>
                    <td colspan="7" style="border: none; padding: 0px;">
                        <div style="page-break-inside: avoid;">
                            <table class="table">
                                <tr>
                                    <td style="width: 5%;">&nbsp;</td>
                                    <td style="width: 31%;">&nbsp;</td>
                                    <td style="width: 7%;">&nbsp;</td>
                                    <td style="width: 9%;">&nbsp;</td>
                                    <td style="width: 15%;">&nbsp;</td>
                                    <td style="width: 15%;">&nbsp;</td>
                                    <td style="width: 18%;">&nbsp;</td>
                                </tr>

                                <tr>
                                    <td>&nbsp;</td>
                                    <td><b>Jumlah</b></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-right"><b>{{ number_format($model->sum($model->id), 0, ',', '.') }}</b></td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>&nbsp;</td>
                                    <td><b>PPN 10%</b></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-right">
                                        <b>{{ number_format($model->getPpn($model->id, $model->ppn, $model->ppn_pe), 0, ',', '.') }}</b>
                                    </td>
                                    <td class="p-1"></td>
                                </tr>

                                <tr>
                                    <td>&nbsp;</td>
                                    <td><b>Total</b></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-right"><b>{{ number_format($model->sum($model->id)+$model->getPpn($model->id, $model->ppn, $model->ppn_pe), 0, ',', '.') }}</b></td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td style="border: none;">&nbsp;</td>
                                    <td style="border: none;">&nbsp;</td>
                                    <td style="border: none;">&nbsp;</td>
                                    <td style="border: none;">&nbsp;</td>
                                    <td style="border: none;">&nbsp;</td>
                                    <td style="border: none;">&nbsp;</td>
                                    <td style="border: none;">&nbsp;</td>
                                </tr>

                                @if (!empty($model->revisi))
                                    <tr>
                                        <td style="border: none;" colspan="7">{{ $model->revisi }}</td>
                                    </tr>

                                    <tr>
                                        <td style="border: none;">&nbsp;</td>
                                        <td style="border: none;">&nbsp;</td>
                                        <td style="border: none;">&nbsp;</td>
                                        <td style="border: none;">&nbsp;</td>
                                        <td style="border: none;">&nbsp;</td>
                                        <td style="border: none;">&nbsp;</td>
                                        <td style="border: none;">&nbsp;</td>
                                    </tr>
                                @endif

                                <tr>
                                    <td style="border: none;" colspan="3">Mengetahui,</td>
                                    <td style="border: none;">&nbsp;</td>
                                    <td style="border: none;">&nbsp;</td>
                                    <td style="border: none;" colspan="2">
                                        Bandung, {{ $model->getDate($model->date,  $model->date_pe) }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="border: none;" colspan="3">Direktur SDM, Keuangan dan Umum</td>
                                    <td style="border: none;">&nbsp;</td>
                                    <td style="border: none;">&nbsp;</td>
                                    <td style="border: none;"  colspan="2">Ka. Bag Perencanaan & Evaluasi</td>
                                </tr>

                                <tr>
                                    <td style="border: none;">&nbsp;</td>
                                    <td style="border: none;">&nbsp;</td>
                                    <td style="border: none;">&nbsp;</td>
                                    <td style="border: none;">&nbsp;</td>
                                    <td style="border: none;">&nbsp;</td>
                                    <td style="border: none;">&nbsp;</td>
                                    <td style="border: none;">&nbsp;</td>
                                </tr>

                                <tr>
                                    <td style="border: none;">&nbsp;</td>
                                    <td style="border: none;">&nbsp;</td>
                                    <td style="border: none;">&nbsp;</td>
                                    <td style="border: none;">&nbsp;</td>
                                    <td style="border: none;">&nbsp;</td>
                                    <td style="border: none;">&nbsp;</td>
                                    <td style="border: none;">&nbsp;</td>
                                </tr>

                                <tr>
                                    <td style="border: none;">&nbsp;</td>
                                    <td style="border: none;">&nbsp;</td>
                                    <td style="border: none;">&nbsp;</td>
                                    <td style="border: none;">&nbsp;</td>
                                    <td style="border: none;">&nbsp;</td>
                                    <td style="border: none;">&nbsp;</td>
                                    <td style="border: none;">&nbsp;</td>
                                </tr>

                                <tr>
                                    <td style="border: none;" colspan="3"><b>Lilis Risnawati, SE.,MA.Ak</b></td>
                                    <td style="border: none;">&nbsp;</td>
                                    <td style="border: none;">&nbsp;</td>
                                    <td style="border: none;"  colspan="2">
                                        <b>
                                            @empty($model->request_name)
                                                dr. Dijah Rochmad
                                            @else
                                                {{ $model->request_name }}
                                            @endempty
                                        </b>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="border: none;" colspan="3"><b>NIP. 196804271996032002</b></td>
                                    <td style="border: none;">&nbsp;</td>
                                    <td style="border: none;">&nbsp;</td>
                                    <td style="border: none;"  colspan="2">
                                        <b>
                                            @empty($model->request_nip)
                                                NIP. 197001182001121002
                                            @else
                                                NIP. {{ $model->request_nip }}
                                            @endempty
                                        </b>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </tbody> --}}
        </table>
    </body>
</html>
