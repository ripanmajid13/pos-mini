@extends('layouts.app')


@push('style_plugin')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <!-- Tempusdominus Bbootstrap 4 -->
    <link rel="stylesheet" href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
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
    <!-- bs-custom-file-input -->
    <script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
@endpush

@push('script_inline')
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
                {data: "description", class: "align-middle"},
                {data: "updateby", class: "align-middle"},
                {data: "action", searchable: false, orderable: false}
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
            pageLength: 10,
            lengthMenu: [[5, 10, 50, 100, -1], [5, 10, 50, 100, "All"]],
            "language": {
                "emptyTable": '<p class="my-3">Tidak ada data yang tersedia pada tabel ini</p>'
            }
        });

        $('body').on('click', '.btn-form', function(e) {
            e.preventDefault();

            $('#body-table').hide();
            $('#card').append(`
                <div id="body-form">
                    <div class="card-body p-1">
                        <p class="text-center my-5 py-5">Loading &nbsp; <i class="fas fa-spinner fa-pulse"></i></p>
                    </div>
                </div>
            `);
            $.post($(this).attr('href'), { "_token" : $('meta[name="csrf-token"]').attr('content') },  function(response) {
                $('.card-title').html('Edit Data');
                $('#body-form').html(response);
                bsCustomFileInput.init();
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
                  text = me.find('.btn-submit').html(),
                  url = me.attr('action');

            me.find('.form-control').removeClass('is-invalid');
            me.find('.error').remove();

            me.find('.btn-submit').html(text+'&nbsp; <i id="loading" class="fas fa-spinner fa-pulse"></i>');
            me.find('.btn-submit').attr('type', 'button');
            me.find('.btn-back').attr('type', 'button');

            $.post(url, me.serialize(), function(response) {
                const data = JSON.parse(response);
                const {sts, icon, msg} = data;

                if (sts == 'errors') {
                    if ($.isEmptyObject(data) == false) {
                        form_validation(data);
                    }
                } else {
                    $.when(
                        alert_toast(icon, msg)
                    ).done(function(){
                        location.reload();
                    });
                    // dataTable.ajax.reload();
                }

                me.find('.btn-submit').html(text);
                me.find('.btn-submit').attr('type', 'submit');
                me.find('.btn-back').attr('type', 'reset');
            })
            .fail(function(xhr) {
                const data = JSON.parse(xhr.responseText);
                alert_toast('error', data.message);

                me.find('.btn-submit').html(text);
                me.find('.btn-submit').attr('type', 'submit');
                me.find('.btn-back').attr('type', 'reset');
            });
        });

        $('body').on('submit', '#form-action-image', function(e) {
            e.preventDefault();

            const me = $(this),
                  url = me.attr('action'),
                  text = me.find('.btn-submit').html();

            $.ajax({
                url: url,
                type: "POST",
                data: new FormData(this),
                beforeSend: function() {
                    me.find('.form-control').removeClass('is-invalid');
                    me.find('.error').remove();

                    $('.btn-submit').attr('type', 'button').html(text+'&nbsp; <i id="loading" class="fas fa-spinner fa-pulse"></i>');
                },
                contentType: false,
                processData: false,
                success: function(response) {
                    const data = JSON.parse(response);
                    const {sts, icon, msg} = data;

                    if (sts == 'errors') {
                        if ($.isEmptyObject(data) == false) {
                            form_validation(data);
                        }
                    } else {
                        $.when(
                            alert_toast(icon, msg)
                        ).done(function(){
                            location.reload();
                        });

                        // $('#description').val('');
                        // $('.custom-file-label').html('Choose file');
                    }

                    $('.btn-submit').attr('type', 'submit').html(text);
                },
                error: function(xhr) {
                    const data = JSON.parse(xhr.responseText);
                    alert_toast('error', data.message);

                    $('.btn-submit').attr('type', 'submit').html(text);
                }
            });
        });

        $('body').on('click', '.btn-back', function(e) {
            e.preventDefault();

            if ($(this).attr('type') == 'reset') {
                $('#body-form').remove();
                $('.card-title').html(`List Data`);
                $('#body-table').show();
            }
        });

//         $(document).ready(function (e) {
//  $("#form").on('submit',(function(e) {
//   e.preventDefault();
//   $.ajax({
//          url: "ajaxupload.php",
//    type: "POST",
//    data:  new FormData(this),
//    contentType: false,
//          cache: false,
//    processData:false,
//    beforeSend : function()
//    {
//     //$("#preview").fadeOut();
//     $("#err").fadeOut();
//    },
//    success: function(data)
//       {
//     if(data=='invalid')
//     {
//      // invalid file format.
//      $("#err").html("Invalid File !").fadeIn();
//     }
//     else
//     {
//      // view uploaded file.
//      $("#preview").html(data).fadeIn();
//      $("#form")[0].reset();
//     }
//       },
//      error: function(e)
//       {
//     $("#err").html(e).fadeIn();
//       }
//     });
//  }));
// });
// ------------------------------------------------------------
// $(document).ready(function (e){
// $("#uploadForm").on('submit',(function(e){
// e.preventDefault();
// $.ajax({
// url: "upload.php",
// type: "POST",
// data:  new FormData(this),
// contentType: false,
// cache: false,
// processData:false,
// success: function(data){
// $("#targetLayer").html(data);
// },
// error: function(){}
// });
// }));
// });
    </script>
@endpush

@section('content')
    <div id="card" class="card">
        <div class="card-header p-1">
            <div class="d-flex justify-content-between">
                <h4 class="card-title text-md" style="padding-top: 1px;">List Data</h4>
            </div>
        </div>

        <div id="body-table" class="card-body p-1">
            <table id="dataTable" class="table table-sm table-bordered table-hover text-xs dt-responsive nowrap mb-0" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Deskripsi</th>
                        <th>Updated By</th>
                        <th style="width: 5%;"></th>
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
@endsection
