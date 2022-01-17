@extends('layouts.app')

@push('style_plugin')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
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
    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
@endpush

@push('script_inline')
    <script>
        $('.select2').select2({
            placeholder: "Choose"
        });

        let dataTable = $('#dataTable').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": false,
            "ordering": false,
            "info": false,
            "autoWidth": false,
            "responsive": true,
            pageLength: -1,
            processing: true,
            serverSide: true,
            ajax: "{{ $urlTable }}",
            columns: [
                {data: "position", class: "align-middle"},
                {data: "icon", class: "align-middle"},
                {data: "name", class: "align-middle"},
                {data: "folder", class: "align-middle"},
                {data: "url", class: "align-middle"},
                {data: "action", class: "align-middle"},
                {data: "option", searchable: false, orderable: false}
            ]
        }),
        dataTableAction;

        formEnable = () => {
            $('#form-parent').find('#name_parent').prop('disabled', false);
            $('#form-parent').find('#position_parent').prop('disabled', false);
            $('#form-parent').find('#icon').prop('disabled', false);
            $('#form-parent').find('#y').prop('checked', true).prop('disabled', false);
            $('#form-parent').find('#n').prop('checked', false).prop('disabled', false);
            $('#form-parent').find('#parent-submit').attr('type', 'submit');
            $('#form-parent').find('#parent-reset').attr('type', 'reset');

            $('#form-child').find('#parent_id').prop('disabled', false);
            $('#form-child').find('#name_child').prop('disabled', false);
            $('#form-child').find('#position_child').prop('disabled', false);
            $('#form-child').find('#child-submit').attr('type', 'submit');
            $('#form-child').find('#child-reset').attr('type', 'reset');
        }

        formDisabled = () => {
            $('#form-parent').find('.form-control').removeClass('is-invalid');
            $('#form-parent').find('.error').remove();
            $('#form-parent').find('#name_parent').val('').prop('disabled', true);
            $('#form-parent').find('#position_parent').val('').prop('disabled', true);
            $('#form-parent').find('#icon').val('').prop('disabled', true);
            $('#form-parent').find('#y').prop('checked', true).prop('disabled', true);
            $('#form-parent').find('#n').prop('checked', false).prop('disabled', true);
            $('#form-parent').find('#parent-submit').attr('type', 'button');
            $('#form-parent').find('#parent-reset').attr('type', 'button');

            $('#form-child').find('.form-control').removeClass('is-invalid');
            $('#form-child').find('.error').remove();
            $('#form-child').find('.form-group .select2-container .selection .select2-selection').removeClass('error-select2');
            $('#form-child').find('#parent_id').val('').trigger('change').prop('disabled', true);
            $('#form-child').find('#name_child').val('').prop('disabled', true);
            $('#form-child').find('#position_child').val('').prop('disabled', true);
            $('#form-child').find('#child-submit').attr('type', 'button');
            $('#form-child').find('#child-reset').attr('type', 'button');
        }

        tableBtnBefore = () => {
            $('.btn-detail').addClass('disabled');
            $('.btn-detail').css("opacity", "5");
            $('.btn-edit-parent').addClass('disabled');
            $('.btn-edit-parent').css("opacity", "5");
            $('.btn-edit-child').addClass('disabled');
            $('.btn-edit-child').css("opacity", "5");
            $('.btn-delete').addClass('disabled');
            $('.btn-delete').css("opacity", "5");
        }

        tableBtnAfter = () => {
            $('.btn-detail').removeClass('disabled');
            $('.btn-edit-parent').removeClass('disabled');
            $('.btn-edit-child').removeClass('disabled');
            $('.btn-delete').removeClass('disabled');
        }

        parentAfterSubmit = () => {
            tableBtnAfter();
            $('#form-child').find('#parent_id').prop('disabled', false);
            $('#form-child').find('#name_child').prop('disabled', false);
            $('#form-child').find('#position_child').prop('disabled', false);
            $('#form-child').find('#folder_child').prop('disabled', false);
            $('#form-child').find('#child-submit').attr('type', 'submit');
            $('#form-child').find('#child-reset').attr('type', 'reset');
        }

        childAfterSubmit = () => {
            //form parent
            tableBtnAfter();
            $('#form-parent').find('#name_parent').prop('disabled', false);
            $('#form-parent').find('#position_parent').prop('disabled', false);
            $('#form-parent').find('#folder_parent').prop('disabled', false);
            $('#form-parent').find('#icon').prop('disabled', false);
            $('#form-parent').find('#y').prop('checked', true).prop('disabled', false);
            $('#form-parent').find('#n').prop('checked', false).prop('disabled', false);
            $('#form-parent').find('#parent-submit').attr('type', 'submit');
            $('#form-parent').find('#parent-reset').attr('type', 'reset');
        }

        // -----------------------------------------

        $('#form-parent').on('submit', function(e) {
            e.preventDefault();

            const me = $(this),
                  url = me.attr("action"),
                  submit = me.find("#parent-submit").html();

            if (submit == 'SAVE') {
                me.find('.form-control').removeClass('is-invalid');
                me.find('.error').remove();

                // form child
                tableBtnBefore();
                $('#form-child').find('.form-control').removeClass('is-invalid');
                $('#form-child').find('.error').remove();
                $('#form-child').find('.form-group .select2-container .selection .select2-selection').removeClass('error-select2');
                $('#form-child').find('#parent_id').val('').trigger('change').prop('disabled', true);
                $('#form-child').find('#name_child').val('').prop('disabled', true);
                $('#form-child').find('#position_child').val('').prop('disabled', true);
                $('#form-child').find('#child-submit').attr('type', 'button');
                $('#form-child').find('#child-reset').attr('type', 'button');
            }

            me.find("#parent-submit").html(submit+'&nbsp; <i id="loading" class="fas fa-spinner fa-pulse"></i>');
            me.find('#parent-submit').attr('type', 'button');
            me.find('#parent-reset').attr('type', 'button');

            $.post(url, me.serialize(), function(response) {
                const data = JSON.parse(response);
                const {sts, icon, msg, parent} = data;

                if (sts == 'errors') {
                    if ($.isEmptyObject(data) == false) {
                        form_validation(data);
                    }

                    me.find("#parent-submit").html(submit);
                    me.find('#parent-submit').attr('type', 'submit');
                    me.find('#parent-reset').attr('type', 'reset');

                    parentAfterSubmit();
                } else {
                    $('#parent_id').html(parent);

                    setTimeout(() => {
                        alert_toast(icon, msg);
                    }, 300);

                    if (icon == 'warning') {
                        me.find("#parent-submit").attr('type', 'submit').html(submit);
                    }

                    if (icon == 'success') {
                        me.find("#parent-submit").html('SAVE');
                        me.find('#parent-submit').attr('type', 'submit');
                        me.find('#parent-reset').attr('type', 'reset');

                        me.find("#name_parent").val('');
                        me.find("#position_parent").val('');
                        me.find('#folder_parent').val('').prop('readonly', false);
                        me.find("#icon").val('');
                        me.find('#n').prop('checked', false).prop('disabled', '');
                        me.find('#y').prop('checked', true).prop('disabled', '');

                        dataTable.ajax.reload();
                        $('#btn-reload').removeClass('collapse');

                        parentAfterSubmit();
                    }

                    if (icon == 'success' && sts == 'update') {
                        $('#card-parent').removeClass('card-warning');
                        $('#card-parent').find('.card-title').html('Create Parent');
                        $('#form-parent').attr('action', $('#parent-reset').attr('data-store'));
                        $('#parent_put').remove();
                        $('#parent-submit').removeClass('btn-warning').addClass('btn-primary');
                        $('#parent-reset').html('RESET').attr('type', 'reset').removeClass('btn-danger').addClass('btn-warning');
                    }
                }
            })
            .fail(function(xhr) {
                const data = JSON.parse(xhr.responseText);
                alert_toast('error', data.message);

                me.find("#parent-submit").html(submit);
                me.find('#parent-submit').attr('type', 'submit');
                me.find('#parent-reset').attr('type', 'reset');

                parentAfterSubmit();
            });
        });

        $('#parent-reset').on('click', function(e) {
            e.preventDefault();

            $('#form-parent').find('.form-control').removeClass('is-invalid');
            $('#form-parent').find('.error').remove();

            $('#name_parent').val('');
            $('#position_parent').val('');
            $('#folder_parent').val('').prop('readonly', false);
            $('#icon').val('');
            $('#n').prop('checked', false).prop('disabled', false);
            $('#y').prop('checked', true).prop('disabled', false);

            // cancel
            if ($(this).attr('type') == 'button') {
                $('#card-parent').removeClass('card-warning');
                $('#card-parent').find('.card-title').html('Create Parent');
                $('#form-parent').attr('action', $('#parent-reset').attr('data-store'));
                $('#parent_put').remove();
                $('#form-parent').find('#parent-submit').html('SAVE').attr('type', 'submit').removeClass('btn-warning').addClass('btn-primary');
                $('#form-parent').find('#parent-reset').attr('type', 'reset').html('RESET').removeClass('btn-danger').addClass('btn-warning');

                parentAfterSubmit();
            }
        });

        $('body').on('click', '.btn-edit-parent', function(e) {
            e.preventDefault();

            const url = $(this).attr('href'),
                  data = JSON.parse($(this).attr('data-form')),
                  {child, name, position, icon, link, folder} = data;

            $('#card-parent').find('.form-control').removeClass('is-invalid');
            $('#card-parent').find('.error').remove();
            $('#card-parent').addClass('card-warning');
            $('#card-parent').find('.card-title').html('Edit Parent');
            $('#form-parent').attr('action', url);
            $('#form-parent').prepend('<input id="parent_put" type="hidden" name="_method" value="PUT">');
            $('#form-parent').find("#name_parent").val(name);
            if(folder.length !== 0) {
                $('#form-parent').find("#folder_parent").val(folder);
            } else {
                $('#form-parent').find("#folder_parent").val('').prop('readonly', true);
            }
            $('#form-parent').find("#position_parent").val(position);
            $('#form-parent').find("#icon").val(icon);
            if (child) {
                $('#form-parent').find('#n').prop('checked', true);
                $('#form-parent').find('#y').prop('checked', false).prop('disabled', 'disabled');
            } else {
                if (link) {
                    $('#form-parent').find('#y').prop('checked', true);
                    $('#form-parent').find('#n').prop('checked', false);
                } else {
                    $('#form-parent').find('#n').prop('checked', true);
                    $('#form-parent').find('#y').prop('checked', false);
                }
            }
            $('#parent-submit').html('UPDATE').removeClass('btn-primary').addClass('btn-warning');
            $('#parent-reset').html('CANCEL').attr('type', 'button').removeClass('btn-warning').addClass('btn-danger');

            // form child
            tableBtnBefore();
            $('#form-child').find('.form-control').removeClass('is-invalid');
            $('#form-child').find('.error').remove();
            $('#form-child').find('.form-group .select2-container .selection .select2-selection').removeClass('error-select2');
            $('#form-child').find('.form-control').removeClass('is-invalid');
            $('#form-child').find('.error').remove();
            $('#form-child').find('.form-group .select2-container .selection .select2-selection').removeClass('error-select2');
            $('#form-child').find('#parent_id').val('').trigger('change').prop('disabled', true);
            $('#form-child').find('#name_child').val('').prop('disabled', true);
            $('#form-child').find('#position_child').val('').prop('disabled', true);
            $('#form-child').find('#folder_child').val('').prop('disabled', true);
            $('#form-child').find('#child-submit').attr('type', 'button');
            $('#form-child').find('#child-reset').attr('type', 'button');
        });

        $('input:radio[name="is_link"]').change(function() {
            if ($(this).val() == 'y') {
                $('#form-parent').find("#folder_parent").prop('readonly', false);
            } else {
                $('#form-parent').find("#folder_parent").prop('readonly', true);
            }
        });

        // -----------------------------------------

        $('#form-child').on('submit', function(e) {
            e.preventDefault();

            const me = $(this),
                  url = me.attr("action"),
                  submit = me.find("#child-submit").html();

            if (submit == 'SAVE') {
                me.find('.form-control').removeClass('is-invalid');
                me.find('.error').remove();
                me.find('.form-group .select2-container .selection .select2-selection').removeClass('error-select2');

                // form parent
                tableBtnBefore();
                $('#form-parent').find('.form-control').removeClass('is-invalid');
                $('#form-parent').find('.error').remove();
                $('#form-parent').find('#name_parent').val('').prop('disabled', true);
                $('#form-parent').find('#position_parent').val('').prop('disabled', true);
                $('#form-parent').find('#icon').val('').prop('disabled', true);
                $('#form-parent').find('#y').prop('checked', true).prop('disabled', true);
                $('#form-parent').find('#n').prop('checked', false).prop('disabled', true);
                $('#form-parent').find('#parent-submit').attr('type', 'button');
                $('#form-parent').find('#parent-reset').attr('type', 'button');
            }

            me.find("#child-submit").html(submit+'&nbsp; <i id="loading" class="fas fa-spinner fa-pulse"></i>');
            me.find('#child-submit').attr('type', 'button');
            me.find('#child-reset').attr('type', 'button');

            $.post(url, me.serialize(), function(response) {
                const data = JSON.parse(response);
                const {sts, icon, msg} = data;

                if (sts == 'errors') {
                    if ($.isEmptyObject(data) == false) {
                        form_validation(data);
                    }

                    me.find("#child-submit").html(submit);
                    me.find('#child-submit').attr('type', 'submit');
                    me.find('#child-reset').attr('type', 'reset');

                    childAfterSubmit();
                } else {
                    setTimeout(() => {
                        alert_toast(icon, msg);
                    }, 300);

                    if (icon == 'warning') {
                        me.find("#child-submit").attr('type', 'submit').html(submit);
                    }

                   if (icon == 'success') {
                        me.find("#child-submit").html('SAVE');
                        me.find('#child-submit').attr('type', 'submit');
                        me.find('#child-reset').attr('type', 'reset');

                        me.find("#parent_id").val('').trigger('change');
                        me.find("#name_child").val('');
                        me.find("#position_child").val('');
                        me.find("#folder_child").val('');

                        dataTable.ajax.reload();
                        $('#btn-reload').removeClass('collapse');

                        childAfterSubmit();
                   }

                    if (sts == 'update' && icon == 'success') {
                        $('#card-child').removeClass('card-warning');
                        $('#card-child').find('.card-title').html('Create Child');
                        $('#form-child').attr('action', $('#child-reset').attr('data-store'));
                        $('#child_put').remove();
                        $('#child-submit').removeClass('btn-warning').addClass('btn-primary');
                        $('#child-reset').html('RESET').attr('type', 'reset').removeClass('btn-danger').addClass('btn-warning');
                    }
                }
            })
            .fail(function(xhr) {
                const data = JSON.parse(xhr.responseText);
                alert_toast('error', data.message);

                me.find("#child-submit").html(submit);
                me.find('#child-submit').attr('type', 'submit');
                me.find('#child-reset').attr('type', 'reset');

                childAfterSubmit();
            });
        });

        $('#child-reset').on('click', function(e) {
            e.preventDefault();

            $('#form-child').find('.form-control').removeClass('is-invalid');
            $('#form-child').find('.error').remove();
            $('#form-child').find('.form-group .select2-container .selection .select2-selection').removeClass('error-select2');

            $("#parent_id").val('').trigger('change');
            $("#name_child").val('');
            $("#position_child").val('');

            //cancel
            if ($(this).attr('type') == 'button') {
                $('#card-child').addClass('card-warning');
                $('#card-child').find('.card-title').html('Edit Child');
                $('#form-child').attr('action', $('#child-reset').attr('data-store'));
                $('#child_put').remove();
                $('#form-child').find('#child-submit').html('SAVE').attr('type', 'submit').removeClass('btn-warning').addClass('btn-primary');
                $('#form-child').find('#child-reset').attr('type', 'reset').html('RESET').removeClass('btn-danger').addClass('btn-warning');

                childAfterSubmit();
            }
        });

        $('body').on('click', '.btn-edit-child', function(e) {
            e.preventDefault();

            const url = $(this).attr('href'),
                  data = JSON.parse($(this).attr('data-form')),
                  {parent, name, position, folder} = data;

            $('#card-child').find('.form-control').removeClass('is-invalid');
            $('#card-child').find('.error').remove();
            $('#card-child').find('.form-group .select2-container .selection .select2-selection').removeClass('error-select2');
            $('#card-child').addClass('card-warning');
            $('#card-child').find('.card-title').html('Edit Child');
            $('#form-child').attr('action', url);
            $('#form-child').prepend('<input id="child_put" type="hidden" name="_method" value="PUT">');
            $('#form-child').find("#parent_id").val(parent).trigger('change');
            $('#form-child').find("#name_child").val(name);
            $('#form-child').find("#position_child").val(position);
            $('#form-child').find("#folder_child").val(folder);
            $('#child-submit').html('UPDATE').removeClass('btn-primary').addClass('btn-warning');
            $('#child-reset').html('CANCEL').attr('type', 'button').removeClass('btn-warning').addClass('btn-danger');

            // form parent
            tableBtnBefore();
            $('#form-parent').find('.form-control').removeClass('is-invalid');
            $('#form-parent').find('.error').remove();
            $('#form-parent').find('#name_parent').val('').prop('disabled', true);
            $('#form-parent').find('#position_parent').val('').prop('disabled', true);
            $('#form-parent').find('#folder_parent').val('').prop('disabled', true);
            $('#form-parent').find('#icon').val('').prop('disabled', true);
            $('#form-parent').find('#y').prop('checked', true).prop('disabled', true);
            $('#form-parent').find('#n').prop('checked', false).prop('disabled', true);
            $('#form-parent').find('#parent-submit').attr('type', 'button');
            $('#form-parent').find('#parent-reset').attr('type', 'button');
        });

        // -----------------------------------------

        $('body').on('click', '.btn-detail', function(e) {
            e.preventDefault();

            const me = $(this),
                  url = me.attr('href');

            $('#card-table').append(`
                <div id="body-detail">
                    <div class="card-body p-1">
                        <p class="text-center my-5 py-5">Loading &nbsp; <i class="fas fa-spinner fa-pulse"></i></p>
                    </div>
                </div>
            `);
            $('#body-table').hide();
            formDisabled();
            $.post(url, { "_token" : $('meta[name="csrf-token"]').attr('content') },  function(response) {
                const data = JSON.parse(response);
                const {html, table} = data;

                $('#card-table').find('.card-title').html('Detail Data');
                $('#body-detail').html(html);
                dataTableAction = $('#actionTable').DataTable({
                    "paging": false,
                    "lengthChange": false,
                    "searching": false,
                    "ordering": false,
                    "info": false,
                    "autoWidth": false,
                    "responsive": true,
                    pageLength: -1,
                    processing: true,
                    serverSide: true,
                    ajax: table,
                    columns: [
                        {data: "DT_RowIndex", class: "align-middle"},
                        {data: "name", class: "align-middle"},
                        {data: "guard_name", class: "align-middle"},
                        {data: "option", searchable: false, orderable: false}
                    ]
                });
            })
            .fail(function(xhr) {
                const data = JSON.parse(xhr.responseText);
                $('#body-detail').html(`
                    <div class="card-body p-1">
                        <p class="text-center text-danger my-5 py-5">Error : ${data.message}</p>
                    </div>
                `);
            });
        });

        $('body').on('click', '.btn-delete', function(e) {
            e.preventDefault();

            const me = $(this),
                  url = me.attr('href'),
                  text = me.attr('data-text'),
                  btn = me.html();

            Swal.fire({
                icon: 'warning',
                title: 'Apakah kamu yakin ?',
                text: `Hapus navigation ${text}.`,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Delete',
                focusCancel: true,
                width: '30%',
                position: 'top'
                // padding: '5px',
            }).then((result) => {
                if (result.value) {
                    me.html('<i class="fas fa-spinner fa-pulse"></i>');
                    $.post(url, { "_method" : "DELETE", "_token" : $('meta[name="csrf-token"]').attr('content') }, function(response) {
                        const data = JSON.parse(response);
                        const {icon, msg} = data;

                        me.html(btn);
                        alert_toast(icon, msg);

                        if (icon == 'success') {
                            dataTable.ajax.reload();
                            $('#btn-reload').removeClass('collapse');
                        }
                    })
                    .fail(function(xhr) {
                        const data = JSON.parse(xhr.responseText);
                        alert_toast('error', data.message);
                    });
                }
            })
        });

        // -----------------------------------------

        $('body').on('submit', '#form-action', function(e) {
            e.preventDefault();

            const me = $(this),
                  url = me.attr("action"),
                  submit = me.find("#action-submit").html();

            me.find('.form-control').removeClass('is-invalid');
            me.find('.error').remove();

            me.find("#action-submit").html(submit+'&nbsp; <i id="loading" class="fas fa-spinner fa-pulse"></i>');
            me.find('#action-submit').attr('type', 'button');
            me.find('#action-reset').attr('type', 'button');
            me.find('.btn-back').attr('type', 'button');

            $.post(url, me.serialize(), function(response) {
                const data = JSON.parse(response);
                const {sts, msg, table} = data;

                if (sts == 'errors') {
                    if ($.isEmptyObject(data) == false) {
                        form_validation(data);
                    }
                } else {
                    setTimeout(() => {
                        alert_toast(sts, msg);
                    }, 300);

                    if (sts == 'success') {
                        dataTableAction.ajax.reload();
                        me.find('#name').val('');
                    }
                }

                me.find("#action-submit").html(submit);
                me.find('#action-submit').attr('type', 'submit');
                me.find('#action-reset').attr('type', 'reset');
                me.find('.btn-back').attr('type', 'reset');
            })
            .fail(function(xhr) {
                const data = JSON.parse(xhr.responseText);
                alert_toast('error', data.message);
                me.find("#action-submit").html(submit);
                me.find('#action-submit').attr('type', 'submit');
                me.find('#action-reset').attr('type', 'reset');
                me.find('.btn-back').attr('type', 'reset');
            });
        });

        $('body').on('click', '.btn-delete-action', function(e) {
            e.preventDefault();

            const me = $(this),
                  url = me.attr('href'),
                  text = me.attr('data-text'),
                  btn = me.html();

            Swal.fire({
                icon: 'warning',
                title: 'Apakah kamu yakin ?',
                text: `Hapus action ${text}.`,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Delete',
                focusCancel: true,
                width: '30%',
                position: 'top'
                // padding: '5px',
            }).then((result) => {
                if (result.value) {
                    me.html('<i class="fas fa-spinner fa-pulse"></i>');
                    $.post(url, { "_method" : "DELETE", "_token" : $('meta[name="csrf-token"]').attr('content') }, function(response) {
                        const data = JSON.parse(response);
                        const {icon, msg} = data;

                        me.html(btn);
                        alert_toast(icon, msg);

                        if (icon == 'success') {
                            dataTableAction.ajax.reload();
                        }
                    })
                    .fail(function(xhr) {
                        const data = JSON.parse(xhr.responseText);
                        alert_toast('error', data.message);
                    });
                }
            })
        });

        // -----------------------------------------

        $('body').on('click', '.btn-back', function(e) {
            e.preventDefault();
            if ($(this).attr('type') == 'reset') {
                $('#body-detail').remove();
                dataTable.ajax.reload();
                $('#body-table').show();
                formEnable();
            }
        });
    </script>
@endpush

@section('content')
  <div class="row">
      <div class="col-9">
        <div id="card-table" class="card">
            <div class="card-header p-1">
                <div class="d-flex justify-content-between">
                    <h4 class="card-title text-md" style="padding-top: 1px;">List Data</h4>
                    <a href="" id="btn-reload" class="btn btn-success btn-form btn-xs px-1 py-0 collapse">Reload</a>
                </div>
            </div>

            <div id="body-table" class="card-body py-1 px-2" style="max-height: 70vh; overflow-y: auto;">
                <table id="dataTable" class="table table-sm table-bordered table-hover text-xs dt-responsive nowrap mb-0" style="width:100%; margin-top: 0 !important; margin-bottom: 0 !important;">
                    <thead>
                        <tr>
                            <th>Pos</th>
                            <th>Icon</th>
                            <th>Name</th>
                            <th>Folder</th>
                            <th>Url</th>
                            <th>Action</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td colspan="6" class="py-5">&nbsp;</td>
                        </tr>
                    </tbody>
                </table>

                <p></p>
            </div>
        </div>
      </div>

      <div class="col-3">
        <div id="card-parent" class="card">
            <div class="card-header p-1">
                <div class="d-flex justify-content-between">
                    <h4 class="card-title text-md" style="padding-top: 1px;">Create Parent</h4>
                </div>
            </div>

            <form id="form-parent" role="form" action="{{ $urlForm }}">
                @csrf
                <input type="hidden" name="form" value="parent">
                <div class="card-body p-1">
                    <div class="row">
                        <div class="col-8">
                            <div class="form-group mb-1">
                                <label for="name_parent" class="font-weight-normal text-xs mb-0">Name</label>
                                <input type="text" class="form-control form-control-xs" name="name_parent" id="name_parent">
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="form-group mb-1">
                                <label for="position_parent" class="font-weight-normal text-xs mb-0">Position</label>
                                <input type="text" class="form-control form-control-xs" name="position_parent" id="position_parent">
                            </div>
                        </div>

                        <div class="col-7">
                            <div class="form-group mb-1">
                                <label for="icon" class="font-weight-normal text-xs mb-0">Icon</label>
                                <input type="text" class="form-control form-control-xs" name="icon" id="icon">
                            </div>
                        </div>

                        <div class="col-5">
                            <label for="is_link" class="font-weight-normal text-xs mb-0">Is Link ?</label>
                            <div class="form-group mb-1">
                                <div class="d-flex justify-content-between">
                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input" type="radio" id="y" value="y" name="is_link" checked>
                                        <label for="y" class="custom-control-label text-sm font-weight-normal">Yes</label>
                                    </div>

                                    &nbsp;&nbsp;&nbsp;

                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input" type="radio" id="n" value="n" name="is_link">
                                        <label for="n" class="custom-control-label text-sm font-weight-normal">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-1">
                        <label for="folder_parent" class="font-weight-normal text-xs mb-0">Folder</label>
                        <input type="text" class="form-control form-control-xs" name="folder_parent" id="folder_parent">
                    </div>
                </div>

                <div class="card-footer p-1">
                    <div class="d-flex justify-content-between">
                        <button type="submit" id="parent-submit" class="btn btn-primary btn-xs py-0 px-1">SAVE</button>
                        <button type="reset" id="parent-reset" class="btn btn-warning btn-xs py-0 px-1" data-store="{{ $urlForm }}">RESET</button>
                    </div>
                </div>
            </form>
        </div>

        <div id="card-child" class="card">
            <div class="card-header p-1">
                <div class="d-flex justify-content-between">
                    <h4 class="card-title text-md" style="padding-top: 1px;">Create Child</h4>
                </div>
            </div>

            <form id="form-child" role="form" action="{{ $urlForm }}">
                @csrf
                <input type="hidden" name="form" value="child">
                <div class="card-body p-1">
                    <div class="form-group mb-1">
                        <label for="parent_id" class="font-weight-normal text-xs mb-0">Parent</label>
                        <select class="form-control form-control-sm select2" id="parent_id" name="parent_id" style="width: 100%;">
                            @php echo $parent; @endphp
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-8">
                            <div class="form-group mb-1">
                                <label for="name_child" class="font-weight-normal text-xs mb-0">Name</label>
                                <input type="text" class="form-control form-control-xs" name="name_child" id="name_child">
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="form-group mb-1">
                                <label for="position_child" class="font-weight-normal text-xs mb-0">Position</label>
                                <input type="text" class="form-control form-control-xs" name="position_child" id="position_child">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-1">
                        <label for="folder_child" class="font-weight-normal text-xs mb-0">Folder</label>
                        <input type="text" class="form-control form-control-xs" name="folder_child" id="folder_child">
                    </div>
                </div>

                <div class="card-footer p-1">
                    <div class="d-flex justify-content-between">
                        <button type="submit" id="child-submit" class="btn btn-primary btn-xs py-0 px-1">SAVE</button>
                        <button type="reset" id="child-reset" class="btn btn-warning btn-xs py-0 px-1" data-store="{{ $urlForm }}">RESET</button>
                    </div>
                </div>
            </form>
        </div>
      </div>
  </div>
@endsection
