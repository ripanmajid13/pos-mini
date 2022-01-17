@extends('layouts.app')

@push('style_plugin')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <!-- Tempusdominus Bbootstrap 4 -->
    <link rel="stylesheet" href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
@endpush

@push('style_inline')
@endpush

@push('script_plugin')
    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <!-- InputMask -->
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/inputmask/min/jquery.inputmask.bundle.min.js') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
@endpush

@push('script_inline')
    <script>
        $('.select2').select2({
            placeholder: "Silahkan Pilih"
        });
        $('#date_from').datetimepicker({
            format: 'DD/MM/YYYY'
        });
        $('#date_to').datetimepicker({
            format: 'DD/MM/YYYY'
        });

        $('body').on('submit', '#form-action', function(e) {
            e.preventDefault();

            const me =  $(this),
                  text = $(this).find('.btn-submit').html(),
                  url = $(this).attr('action');

            me.find('.form-control').removeClass('is-invalid');
            me.find('.error').remove();
            me.find('.form-group .select2-container .selection .select2-selection').removeClass('error-select2');

            me.find('.btn-submit').html(text+'&nbsp; <i id="loading" class="fas fa-spinner fa-pulse"></i>');
            me.find('.btn-submit').attr('type', 'button');

            $.post(url, me.serialize(), function(response) {
                const data = JSON.parse(response);
                const {sts, urlPrint} = data;

                if (sts == 'errors') {
                    if ($.isEmptyObject(data) == false) {
                        form_validation(data);
                    }
                }
                else {
                    window.open(urlPrint);
                }

                me.find('.btn-submit').html(text);
                me.find('.btn-submit').attr('type', 'submit');
            })
            .fail(function(xhr) {
                const data = JSON.parse(xhr.responseText);
                alert_toast('error', data.message);

                me.find('.btn-submit').html(text);
                me.find('.btn-submit').attr('type', 'submit');
            });
        });

        $('#report').on('change', function() {
            if ($(this).val() == 1) {
                $('#col-date-from').hide();
                $('#col-date-to').hide();
            } else {
                $('#col-date-from').show();
                $('#col-date-to').show();
            }
        })
    </script>
@endpush

@section('content')
    <div id="card" class="card">
        <form id="form-action" action="{{ $urlCreate }}" method="POST" role="form">
            @csrf
            <div class="card-body p-1">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group mb-1 text-sm">
                            <label for="report" class="font-weight-normal mb-0">Jenis Laporan</label>
                            <select class="form-control form-control-sm select2" id="report" name="report" style="width: 100%;">
                                <option value=""></option>
                                <option value="1">Laporan Stok Barang</option>
                                <option value="2">Laporan Barang Masuk</option>
                                <option value="3">Laporan Barang Keluar</option>
                            </select>
                        </div>
                    </div>

                    <div id="col-date-from" class="col-3">
                        <div class="form-group mb-1 text-sm">
                            <label for="date_from" class="font-weight-normal mb-0">Dari Tanggal</label>
                            <div class="input-group date" id="date_from" data-target-input="nearest">
                                <input type="text" name="date_from" class="form-control form-control-sm datetimepicker-input date" data-target="#date_from" data-toggle="datetimepicker" value="{{ date('d/m/Y') }}" />
                            </div>
                        </div>
                    </div>

                    <div id="col-date-to" class="col-3">
                        <div class="form-group mb-1 text-sm">
                            <label for="date_to" class="font-weight-normal mb-0">Sampai Tanggal</label>
                            <div class="input-group date" id="date_to" data-target-input="nearest">
                                <input type="text" name="date_to" class="form-control form-control-sm datetimepicker-input date" data-target="#date_to" data-toggle="datetimepicker" value="{{ date('d/m/Y') }}" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer p-1">
                <button type="submit" class="btn btn-primary btn-submit btn-xs py-0 px-1">Print Preview</button>
            </div>
        </form>
    </div>
@endsection
