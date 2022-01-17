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
        <p class="text-center" style="font-size: 15px; margin-bottom: -10px;"><b>LAPORAN STOK BARANG</b></p>
        <p class="text-center">Tanggal : {{ $date_from }}</p>

        <table class="table" page-break-inside: always;>
            <thead>
                <tr>
                    <td class="text-center" style="">NO</td>
                    <td class="text-center" style="">KODE</td>
                    <td class="text-center" style="">BARANG</td>
                    <td class="text-center" style="">STOK</td>
                </tr>
            </thead>

            <tbody>
                @foreach ($model as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index+1 }}</td>
                        <td>{{ $item->code }}</td>
                        <td>{{ $item->type->name.' '.$item->name }}</td>
                        <td>{{ $item->stock($item->id).' '.$item->unit->name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>
