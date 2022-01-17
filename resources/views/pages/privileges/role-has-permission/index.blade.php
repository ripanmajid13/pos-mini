@extends('layouts.app')

@push('style_plugin')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
@endpush

@push('script_plugin')
    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
@endpush

@push('script_inline')
    <!-- page script -->
    <script>
        let dataTable = $("#dataTable").DataTable({
            "responsive": true,
            "autoWidth": false,
            processing: true,
            serverSide: true,
            ajax: "{{ $urlTable }}",
            columns: [
                {data: "DT_RowIndex", class: "align-middle"},
                {data: "name", class: "align-middle"},
                {data: "navigations", class: "align-middle"},
                {data: "action", class: "align-middle"},
                @can($canEdit)
                    {data: "option", searchable: false, orderable: false, class: "align-middle"}
                @endcan
            ],
            columnDefs: [
                { responsivePriority: 1, targets: 1 },
                { responsivePriority: 2, targets: -1 },
            ],
            responsive: {
                details: {
                    renderer: function ( api, rowIdx, columns ) {
                        var data = $.map( columns, function ( col, i ) {
                            if (col.title.length == 0) {
                                var title = 'Action',
                                    padding = 'class="pt-2 pb-1"';
                            } else {
                                var title = col.title,
                                    padding = 'class="pt-1 pb-1"';
                            }
                            return col.hidden ?
                                '<tr data-dt-row="'+col.rowIndex+'" data-dt-column="'+col.columnIndex+'" class="text-sm">'+
                                    '<td '+padding+'>'+title+' <span class="float-right">:</span>'+'</td> '+
                                    '<td class="pt-1 pb-1">'+col.data+'</td>'+
                                '</tr>' :
                                '';
                        } ).join('');

                        return data ?$('<table/>').append( data ) :false;
                    }
                }
            },
            pageLength: 5,
            lengthMenu: [[5, 10, 50, 100, -1], [5, 10, 50, 100, "All"]],
            "language": {
                "emptyTable": '<p class="my-3">Tidak ada data yang tersedia pada tabel ini</p>'
            }
        });

        $('#btn-create').on('click', function(e){
            e.preventDefault();

            $(this).hide();
            $('#body-table').hide();
            $('#card').append(`
                <div id="body-form">
                    <div class="card-body p-1">
                        <p class="text-center my-5 py-5">Loading &nbsp; <i class="fas fa-spinner fa-pulse"></i></p>
                    </div>
                </div>
            `);
            $.post($(this).attr('href'), { "_token" : $('meta[name="csrf-token"]').attr('content') }, function(response) {
                const data = JSON.parse(response);
                const {title, html} = data;

                $('.card-title').html(`Detail ${title}`);
                $('#body-form').html(html);

                $('.select2').select2({
                    placeholder: "Choose"
                });
            })
            .fail(function(xhr) {
                const data = JSON.parse(xhr.responseText);
                $('#body-form').html(`
                    <div class="card-body p-1">
                        <p class="text-center text-danger my-5 py-5">Error : ${data.message}</p>
                    </div>
                `);
            });
        });

        $('body').on('click', '.btn-detail', function(e){
            e.preventDefault();

            $('#btn-create').hide();
            $('#body-table').hide();
            $('#card').append(`
                <div id="body-form">
                    <div class="card-body p-1">
                        <p class="text-center my-5 py-5">Loading &nbsp; <i class="fas fa-spinner fa-pulse"></i></p>
                    </div>
                </div>
            `);
            $.post($(this).attr('href'), { "_token" : $('meta[name="csrf-token"]').attr('content') }, function(response) {
                const data = JSON.parse(response);
                const {title, html} = data;

                $('.card-title').html(`Detail ${title}`);
                $('#body-form').html(html);

                $('.select2').select2({
                    placeholder: "Choose"
                });
            })
            .fail(function(xhr) {
                const data = JSON.parse(xhr.responseText);
                $('#body-form').html(`
                    <div class="card-body p-1">
                        <p class="text-center text-danger my-5 py-5">Error : ${data.message}</p>
                    </div>
                `);
            });
        });

        $('body').on('submit', '#form-action', function(e) {
            e.preventDefault();

            const me = $(this),
                  text = me.find(':submit').html(),
                  url = me.attr('action');

            me.find('.form-control').removeClass('is-invalid');
            me.find('.error').remove();
            me.find('.form-group .select2-container .selection .select2-selection').removeClass('error-select2');
            me.find(':submit').html(text+'&nbsp; <i id="loading" class="fas fa-spinner fa-pulse"></i>');

            $.post(url, me.serialize(), function(response) {
                const data = JSON.parse(response);
                const {sts, msg, scs, nav} = data;

                if (sts == 'errors') {
                    if ($.isEmptyObject(data) == false) {
                        form_validation(data);
                    }
                } else {
                    if (nav == 0) {
                        $('#btn-create').hide();
                    } else {
                        $('#btn-create').show();
                    }

                    if (scs == 'store' || scs == 0) {
                        $('#body-form').remove();
                        $('#body-table').show();
                    }

                    if (sts == 'success') {
                        dataTable.ajax.reload();
                    }

                    alert_toast(sts, msg);
                }

                me.find(':submit').html(text);
            })
            .fail(function(xhr) {
                const data = JSON.parse(xhr.responseText);
                alert_toast('error', data.message);
                me.find(':submit').html(text);
            });
        });

        $('body').on('click', '.btn-back', function(e) {
            e.preventDefault();

            let count = "{{ $roles }}";

            if(count > 0) {
                $('#btn-create').show();
            } else {
                $('#btn-create').hide();
            }
            $('#body-form').remove();
            $('#body-table').show();
        })
    </script>
@endpush

@section('content')
    <div id="card" class="card">
        <div class="card-header p-1">
            <div class="d-flex justify-content-between">
                <h4 class="card-title text-md" style="padding-top: 1px;">List Data</h4>
                @can($canCreate)
                    <a href="{{ $urlCreate }}" id="btn-create" class="btn btn-primary btn-xs px-1 py-0 {{ $roles ? $roles : 'collapse' }}">Create</a>
                @endcan
            </div>
        </div>

        <div id="body-table" class="card-body p-1">
            <div class="table-responsive">
                <table id="dataTable" class="table table-sm table-bordered table-hover text-xs dt-responsive nowrap mb-0" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Role</th>
                            <th>Navigation</th>
                            <th>Action</th>
                            @can($canEdit)
                                <th style="width: 3%;"></th>
                            @endcan
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td colspan="5" class="py-5">&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
