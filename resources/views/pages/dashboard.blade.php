@extends('layouts.app')

@push('style_plugin')
@endpush

@push('script_plugin')
    <!-- ChartJS -->
    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
@endpush

@push('script_inline')
    <script>
        new Chart(document.getElementById('lineChart').getContext('2d'), {
            type: 'line',
            data:  {
                labels  : ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                datasets: [
                    {
                        label               : 'Barang Masuk',
                        fill                : false,
                        backgroundColor     : '#0d47a1',
                        borderColor         : '#0d47a1',
                        // pointRadius         : true,
                        pointColor          : '#0d47a1',
                        pointStrokeColor    : '#0d47a1',
                        pointHighlightFill  : '#fff',
                        pointHighlightStroke: '#0d47a1',
                        data                : {{ $ii }}
                    },
                    {
                        label               : 'Barang Keluar',
                        fill                : false,
                        backgroundColor     : '#CC0000',
                        borderColor         : '#CC0000',
                        // pointRadius         : true,
                        pointColor          : '#CC0000',
                        pointStrokeColor    : '#CC0000',
                        pointHighlightFill  : '#fff',
                        pointHighlightStroke: '#CC0000',
                        data                : {{ $oi }}
                    },
                ]
            },
            options: {
                maintainAspectRatio : false,
                responsive : true,
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                    }],
                    yAxes: [{
                        display: true,
                        // ticks: {
						// 	min: 1,
						// }
                    }]
                }
            }
        })
    </script>
@endpush

@section('content')
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box bg-info mb-3 p-1" style="min-height: 70px;">
                <span class="info-box-icon bg-dark elevation-1">
                    <i class="fas fa-shopping-bag text-info"></i>
                </span>

                <div class="info-box-content">
                    <span class="info-box-text text-dark">Barang</span>
                    <span class="info-box-number text-dark">{{ $item }}</span>
              </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box bg-success mb-3 p-1" style="min-height: 70px;">
                <span class="info-box-icon bg-dark elevation-1">
                    <i class="fas fa-industry text-success"></i>
                </span>

                <div class="info-box-content">
                    <span class="info-box-text text-dark">Supplier</span>
                    <span class="info-box-number text-dark">{{ $supplier }}</span>
                </div>
            </div>
        </div>

        <div class="clearfix hidden-md-up"></div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box bg-warning mb-3 p-1" style="min-height: 70px;">
                <span class="info-box-icon bg-dark elevation-1">
                    <i class="fas fa-tags text-warning"></i>
                </span>

                <div class="info-box-content">
                    <span class="info-box-text text-dark">Jenis Barang</span>
                    <span class="info-box-number text-dark">{{ $type }}</span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box bg-danger mb-3 p-1"  style="min-height: 70px;">
                <span class="info-box-icon bg-dark elevation-1">
                    <i class="fas fa-users text-danger"></i>
                </span>

                <div class="info-box-content">
                    <span class="info-box-text text-dark">Pengguna</span>
                    <span class="info-box-number text-dark">{{ $user }}</span>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card card-info">
                <div class="card-header py-1 px-2">
                    <h3 class="card-title text-md text-light">Total Transaksi Barang Tahun {{ date('Y') }}</h3>
                </div>

                <div class="card-body p-0">
                    <div class="chart">
                        <canvas id="lineChart" style="height: 250px; max-height: 100%; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card card-warning">
                <div class="card-header border-transparent text-center px-2 py-1">
                    <h3 class="m-0 text-md text-light">Stok Barang Minimum</h3>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table m-0 text-xs">
                            <thead>
                                <tr>
                                    <th class="px-2 py-1">Barang</th>
                                    <th class="px-2 py-1">Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($itemMinimum as $item)
                                    <tr>
                                        <td class="px-2 py-1">{{ $item['name'] }}</td>
                                        <td class="px-2 py-1">{{ $item['stock'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-2 py-1 text-center">Tidak ada barang stok minimum.</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card card-primary">
                <div class="card-header border-transparent text-center px-2 py-1">
                    <h3 class="m-0 text-md text-light">5 Transaksi Terakhir Barang Masuk</h3>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table m-0 text-xs">
                            <thead>
                                <tr>
                                    <th class="px-2 py-1">Tanggal</th>
                                    <th class="px-2 py-1">Barang</th>
                                    <th class="px-2 py-1">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($incomingItem as $ii)
                                    <tr>
                                        <td class="px-2 py-1">{{ date_format(date_create($ii->date), 'd/m/Y') }}</td>
                                        <td class="px-2 py-1">{{ $ii->item->type->name.' '.$ii->item->name }}</td>
                                        <td class="px-2 py-1">{{ $ii->qty.' '.$ii->item->unit->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card card-danger">
                <div class="card-header border-transparent text-center px-2 py-1">
                    <h3 class="m-0 text-md text-light">5 Transaksi Terakhir Barang Keluar</h3>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table m-0 text-xs">
                            <thead>
                                <tr>
                                    <th class="px-2 py-1">Tanggal</th>
                                    <th class="px-2 py-1">Barang</th>
                                    <th class="px-2 py-1">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($outgoingItem as $oi)
                                    <tr>
                                        <td class="px-2 py-1">{{ date_format(date_create($oi->date), 'd/m/Y') }}</td>
                                        <td class="px-2 py-1">{{ $oi->item->type->name.' '.$oi->item->name }}</td>
                                        <td class="px-2 py-1">{{ $oi->qty.' '.$oi->item->unit->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
