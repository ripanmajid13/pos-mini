@extends('layouts.app')

@push('style_plugin')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <!-- Tempusdominus Bbootstrap 4 -->
    <link rel="stylesheet" href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
@endpush

@push('style_inline')
@endpush

@push('script_plugin')
    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- InputMask -->
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/inputmask/min/jquery.inputmask.bundle.min.js') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <!-- bs-custom-file-input -->
    <script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
@endpush

@push('script_plugin')
    <script>
        bsCustomFileInput.init();

        $('#gender').select2({
            placeholder: "Isi Jenis Kelamin"
        });

        $('#date_of_birth').datetimepicker({
            format: 'DD/MM/YYYY'
        });

        $("#image_profile").change(function(e) {
            $('#form-image').find('.form-control').removeClass('is-invalid');
            $('#form-image').find('.error').remove();

            const ext = this.value.match(/\.([^\.]+)$/)[1];
            switch (ext) {
                case 'jpg':
                case 'jpeg':
                case 'png':
                    var reader = new FileReader();
                    var filename = $("#image_profile").val();
                    filename = filename.substring(filename.lastIndexOf('\\')+1);
                    reader.onload = function(e) {
                        $('.img-profile').attr('src', e.target.result);
                        $('.img-profile').hide();
                        $('.img-profile').fadeIn(500);
                        $('.custom-file-label').text(filename);
                    }
                    reader.readAsDataURL(this.files[0]);
                    break;

                default:
                    $(this).addClass('is-invalid');
                    $(this).closest(".form-group").append('<span id="image_profile-error" class="error invalid-feedback" style="display: inline;">Harus file bertipe : png, jpg, jpeg.</span>');
            }
        });

        $('#form-name').on('submit', function(e) {
            e.preventDefault();

            const me = $(this),
                  url = me.attr('action'),
                  text = me.find('.submit-name').html();

            me.find('.form-control').removeClass('is-invalid');
            me.find('.error').remove();

            me.find('#name').prop('readonly', true).css('background-color', '#fff');
            $('.submit-name').attr('type', 'button').html(text+'&nbsp; <i id="loading" class="fas fa-spinner fa-pulse"></i>');

            $.post(url, me.serialize(), function(response) {
                const data = JSON.parse(response);
                const {sts, msg, value} = data;

                if (sts == 'errors') {
                    if ($.isEmptyObject(data) == false) {
                        form_validation(data);
                    }
                } else {
                    alert_toast(sts, msg);
                    me.find('#password-email').val('');
                    $('.auth-name').html(value);
                }

                me.find('#name').prop('readonly', false);
                $('.submit-name').attr('type', 'submit').html(text);
            })
            .fail(function(xhr) {
                const data = JSON.parse(xhr.responseText);
                alert_toast('error', data.message);

                me.find('#name').prop('readonly', false);
                $('.submit-name').attr('type', 'submit').html(text);
            });
        })

        $('#form-email').on('submit', function(e) {
            e.preventDefault();

            const me = $(this),
                  url = me.attr('action'),
                  text = me.find('.submit-email').html();

            me.find('.form-control').removeClass('is-invalid');
            me.find('.error').remove();

            me.find('#email').prop('readonly', true).css('background-color', '#fff');
            me.find('#password_email').prop('readonly', true).css('background-color', '#fff')
            $('.submit-email').attr('type', 'button').html(text+'&nbsp; <i id="loading" class="fas fa-spinner fa-pulse"></i>');

            $.post(url, me.serialize(), function(response) {
                const data = JSON.parse(response);
                const {sts, msg, value} = data;

                if (sts == 'errors') {
                    if ($.isEmptyObject(data) == false) {
                        form_validation(data);
                    }
                } else {
                    alert_toast(sts, msg);

                    if (sts =='success') {
                        me.find('#password_email').val('');
                        $('.auth-email').html(value);
                    }
                }

                me.find('#email').prop('readonly', false);
                me.find('#password_email').prop('readonly', false)
                $('.submit-email').attr('type', 'submit').html(text);
            })
            .fail(function(xhr) {
                const data = JSON.parse(xhr.responseText);
                alert_toast('error', data.message);

                me.find('#email').prop('readonly', false);
                me.find('#password_email').prop('readonly', false)
                $('.submit-email').attr('type', 'submit').html(text);
            });
        });

        $('#form-username').on('submit', function(e) {
            e.preventDefault();

            const me = $(this),
                  url = me.attr('action'),
                  text = me.find('.submit-username').html();

            me.find('.form-control').removeClass('is-invalid');
            me.find('.error').remove();

            me.find('#username').prop('readonly', true).css('background-color', '#fff');
            me.find('#password_username').prop('readonly', true).css('background-color', '#fff')
            $('.submit-username').attr('type', 'button').html(text+'&nbsp; <i id="loading" class="fas fa-spinner fa-pulse"></i>');

            $.post(url, me.serialize(), function(response) {
                const data = JSON.parse(response);
                const {sts, msg, value} = data;

                if (sts == 'errors') {
                    if ($.isEmptyObject(data) == false) {
                        form_validation(data);
                    }
                } else {
                    alert_toast(sts, msg);

                    if (sts =='success') {
                        me.find('#password_username').val('');
                        $('.auth-username').html(value);
                    }
                }

                me.find('#username').prop('readonly', false);
                me.find('#password_username').prop('readonly', false)
                $('.submit-username').attr('type', 'submit').html(text);
            })
            .fail(function(xhr) {
                const data = JSON.parse(xhr.responseText);
                alert_toast('error', data.message);

                me.find('#username').prop('readonly', false);
                me.find('#password_username').prop('readonly', false)
                $('.submit-username').attr('type', 'submit').html(text);
            });
        });

        $('#form-password').on('submit', function(e) {
            e.preventDefault();

            const me = $(this),
                  url = me.attr('action'),
                  text = me.find('.submit-password').html();

            me.find('.form-control').removeClass('is-invalid');
            me.find('.error').remove();

            me.find('#password_confirmation').prop('readonly', true).css('background-color', '#fff');
            me.find('#password').prop('readonly', true).css('background-color', '#fff')
            me.find('#old_password').prop('readonly', true).css('background-color', '#fff')
            $('.submit-password').attr('type', 'button').html(text+'&nbsp; <i id="loading" class="fas fa-spinner fa-pulse"></i>');

            $.post(url, me.serialize(), function(response) {
                const data = JSON.parse(response);
                const {sts, msg, value} = data;

                if (sts == 'errors') {
                    if ($.isEmptyObject(data) == false) {
                        form_validation(data);
                    }
                } else {
                    alert_toast(sts, msg);

                    if (sts =='success') {
                        me.find('#password_confirmation').val('');
                        me.find('#password').val('');
                        me.find('#old_password').val('');
                    }
                }

                me.find('#password_confirmation').prop('readonly', false);
                me.find('#password').prop('readonly', false);
                me.find('#old_password').prop('readonly', false)
                $('.submit-password').attr('type', 'submit').html(text);
            })
            .fail(function(xhr) {
                const data = JSON.parse(xhr.responseText);
                alert_toast('error', data.message);

                me.find('#password_confirmation').prop('readonly', false);
                me.find('#password').prop('readonly', false);
                me.find('#old_password').prop('readonly', false)
                $('.submit-password').attr('type', 'submit').html(text);
            });
        });

        $('#form-profile').on('submit', function(e) {
            e.preventDefault();

            const me = $(this),
                  url = me.attr('action'),
                  text = me.find('.submit-profile').html();

            me.find('.form-control').removeClass('is-invalid');
            me.find('.form-group .select2-container .selection .select2-selection').removeClass('error-select2');
            me.find('.error').remove();

            $.post(url, me.serialize(), function(response) {
                const data = JSON.parse(response);
                const {sts, icon, msg, action, ab} = data;

                if (sts == 'errors') {
                    if ($.isEmptyObject(data) == false) {
                        form_validation(data);
                    }
                } else {
                    alert_toast(icon, msg);

                    if (sts == 'store') {
                        $('#form-profile').prepend('<input name="_method" type="hidden" value="PUT">');
                        $('#form-profile').attr('action', action);
                    }
                    $('#ab').html(ab);
                }

                me.find('#place_of_birth').prop('readonly', false);
                me.find('#date_of_birth').prop('readonly', false);
                me.find('#gender').prop('disabled', false);
                me.find('#address').prop('readonly', false);
                me.find('#hp').prop('readonly', false);
                $('.submit-profile').attr('type', 'submit').html(text);
            })
            .fail(function(xhr) {
                const data = JSON.parse(xhr.responseText);
                alert_toast('error', data.message);

                me.find('#place_of_birth').prop('readonly', false);
                me.find('#date_of_birth').prop('readonly', false);
                me.find('#gender').prop('disabled', false);
                me.find('#address').prop('readonly', false);
                me.find('#hp').prop('readonly', false);
                $('.submit-profile').attr('type', 'submit').html(text);
            });

            me.find('#place_of_birth').prop('readonly', true).css('background-color', '#fff');
            me.find('#date_of_birth').prop('readonly', true).css('background-color', '#fff');
            me.find('#gender').prop('disabled', true);
            me.find('#address').prop('readonly', true).css('background-color', '#fff');
            me.find('#hp').prop('readonly', true).css('background-color', '#fff');
            $('.submit-profile').attr('type', 'button').html(text+'&nbsp; <i id="loading" class="fas fa-spinner fa-pulse"></i>');
        });

        $('#form-image').on('submit', function(e) {
            e.preventDefault();

            const me = $(this),
                  url = me.attr('action'),
                  text = me.find('.submit-image').html();

            $.ajax({
                url: url,
                type: "POST",
                data: new FormData(this),
                beforeSend: function() {
                    me.find('.form-control').removeClass('is-invalid');
                    me.find('.error').remove();

                    $('.submit-image').attr('type', 'button').html(text+'&nbsp; <i id="loading" class="fas fa-spinner fa-pulse"></i>');
                },
                contentType: false,
                processData: false,
                success: function(response) {
                    const data = JSON.parse(response);
                    const {sts, icon, msg, action, image} = data;

                    if (sts == 'errors') {
                        if ($.isEmptyObject(data) == false) {
                            form_validation(data);
                        }
                    } else {
                        alert_toast(icon, msg);

                        if (sts == 'store') {
                            $('#form-image').prepend('<input name="_method" type="hidden" value="PUT">');
                            $('#form-image').attr('action', action);
                        }

                        $('#image_profile').val('');
                        $('.custom-file-label').html('Choose file');
                        $('.img-profile').attr('src', image);
                    }

                    $('.submit-image').attr('type', 'submit').html(text);
                },
                error: function(xhr) {
                    const data = JSON.parse(xhr.responseText);
                    alert_toast('error', data.message);

                    $('.submit-image').attr('type', 'submit').html(text);
                }
            });
        });
    </script>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile p-2">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-rounded img-profile"
                            src="{{ $imageProfile }}"
                            alt="User profile picture"
                            style="height: 130px; width: 120px;" />
                    </div>

                    <h3 class="profile-username text-center auth-name">{{ auth()->user()->name }}</h3>

                    <p class="text-muted text-sm text-center mb-0">{{ ucwords(implode(', ', auth()->user()->roles->pluck('name')->toArray())) }}</p>
                </div>
            </div>

            <div class="card card-primary">
                <div class="card-header px-2 py-1">
                    <h3 class="card-title">About Me</h3>
                </div>

                <div class="card-body px-2 py-1 text-xs">
                    <strong><i class="fas fa-user mr-1"></i> Username</strong>
                    <p class="text-muted mb-0 auth-username">{{ auth()->user()->username }}</p>

                    <hr class="my-1">

                    <strong><i class="fas fa-envelope mr-1"></i> Email</strong>
                    <p class="text-muted mb-0 auth-email">{{ auth()->user()->email }}</p>

                    <div id="ab">
                        {!! $ab !!}
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
        </div>

        <div class="col-md-9">
            <div class="card">
                <div class="card-header p-1">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link py-0 px-1 active" href="#home" data-toggle="tab">Home</a></li>
                        <li class="nav-item"><a class="nav-link py-0 px-1" href="#settings" data-toggle="tab">Settings</a></li>
                    </ul>
                </div>

                <div class="card-body p-1">
                    <div class="tab-content">
                        <div class="active tab-pane" id="home">
                            <p class="text-center py-5 my-5">Selamat Datang</p>
                        </div>

                        <div class="tab-pane" id="settings">
                            <div class="row">
                                <div class="col-5 col-sm-3">
                                    <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                                        <a class="nav-link text-sm mb-1 px-1 py-0 active" id="vert-tabs-name-tab" data-toggle="pill" href="#vert-tabs-name" role="tab" aria-controls="vert-tabs-name" aria-selected="true">Name</a>
                                        <a class="nav-link text-sm mb-1 px-1 py-0" id="vert-tabs-email-tab" data-toggle="pill" href="#vert-tabs-email" role="tab" aria-controls="vert-tabs-email" aria-selected="true">Email</a>
                                        <a class="nav-link text-sm mb-1 px-1 py-0" id="vert-tabs-username-tab" data-toggle="pill" href="#vert-tabs-username" role="tab" aria-controls="vert-tabs-username" aria-selected="false">Username</a>
                                        <a class="nav-link text-sm mb-1 px-1 py-0" id="vert-tabs-password-tab" data-toggle="pill" href="#vert-tabs-password" role="tab" aria-controls="vert-tabs-password" aria-selected="false">Password</a>
                                        <a class="nav-link text-sm mb-1 px-1 py-0" id="vert-tabs-about-you-tab" data-toggle="pill" href="#vert-tabs-about-you" role="tab" aria-controls="vert-tabs-about-you" aria-selected="false">About You</a>
                                        <a class="nav-link text-sm px-1 py-0" id="vert-tabs-image-tab" data-toggle="pill" href="#vert-tabs-image" role="tab" aria-controls="vert-tabs-image" aria-selected="false">Image</a>
                                    </div>
                                </div>

                                <div class="col-7 col-sm-9">
                                    <div class="tab-content" id="vert-tabs-tabContent">
                                        <div class="tab-pane fade show active" id="vert-tabs-name" role="tabpanel" aria-labelledby="vert-tabs-name-tab">
                                            <div class="card card-info mb-0">
                                                <div class="card-header py-1 text-center">
                                                    <h3 class="text-lg mb-0">NAME</h3>
                                                </div>

                                                <form id="form-name" action="{{ $actionName }}" method="POST" role="form">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="card-body p-1">
                                                        <div class="form-group mb-1">
                                                            <label for="name" class="font-weight-normal mb-0 text-sm">Full Name</label>
                                                            <input type="text" class="form-control form-control-sm" id="name" name="name" value="{{ auth()->user()->name }}">
                                                        </div>
                                                    </div>

                                                    <div class="card-footer p-1">
                                                        <button type="submit" class="btn btn-warning submit-name btn-xs">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="vert-tabs-email" role="tabpanel" aria-labelledby="vert-tabs-email-tab">
                                            <div class="card card-info mb-0">
                                                <div class="card-header py-1 text-center">
                                                    <h3 class="text-lg mb-0">EMAIL</h3>
                                                </div>

                                                <form id="form-email" action="{{ $actionEmail }}" method="POST" role="form">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="card-body p-1">
                                                        <div class="form-group mb-1">
                                                            <label for="email" class="font-weight-normal mb-0 text-sm">Email address</label>
                                                            <input type="email" class="form-control form-control-sm" id="email" name="email" value="{{ auth()->user()->email }}">
                                                        </div>

                                                        <div class="form-group mb-1">
                                                            <label for="password_email" class="font-weight-normal mb-0 text-sm">Password Confirmation</label>
                                                            <input type="password" class="form-control form-control-sm" id="password_email" name="password_email" placeholder="Pleas enter your password">
                                                        </div>
                                                    </div>

                                                    <div class="card-footer p-1">
                                                        <button type="submit" class="btn btn-warning submit-email btn-xs">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="vert-tabs-username" role="tabpanel" aria-labelledby="vert-tabs-username-tab">
                                            <div class="card card-info mb-0">
                                                <div class="card-header py-1 text-center">
                                                    <h3 class="text-lg mb-0">USERNAME</h3>
                                                </div>

                                                <form id="form-username" action="{{ $actionUsername }}" method="POST" role="form">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="card-body p-1">
                                                        <div class="form-group mb-1">
                                                            <label for="username" class="font-weight-normal mb-0 text-sm">Username</label>
                                                            <input type="text" class="form-control form-control-sm" id="username" name="username" value="{{ auth()->user()->username }}">
                                                        </div>

                                                        <div class="form-group mb-1">
                                                            <label for="password_username" class="font-weight-normal mb-0 text-sm">Password Confirmation</label>
                                                            <input type="password" class="form-control form-control-sm" id="password_username" name="password_username" placeholder="Pleas enter your password">
                                                        </div>
                                                    </div>

                                                    <div class="card-footer p-1">
                                                        <button type="submit" class="btn btn-warning submit-username btn-xs">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="vert-tabs-password" role="tabpanel" aria-labelledby="vert-tabs-password-tab">
                                            <div class="card card-info mb-0">
                                                <div class="card-header py-1 text-center">
                                                    <h3 class="text-lg mb-0">PASSWORD</h3>
                                                </div>

                                                <form id="form-password" action="{{ $actionPassword }}" method="POST" role="form">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="card-body p-1">
                                                        <div class="form-group mb-1">
                                                            <label for="old_password" class="font-weight-normal mb-0 text-sm">Old Password</label>
                                                            <input type="password" class="form-control form-control-sm" id="old_password" name="old_password">
                                                        </div>

                                                        <div class="form-group mb-1">
                                                            <label for="password_confirmation" class="font-weight-normal mb-0 text-sm">New Password</label>
                                                            <input type="password" class="form-control form-control-sm" id="password_confirmation" name="password_confirmation" autocomplete="new-password">
                                                        </div>

                                                        <div class="form-group mb-1">
                                                            <label for="password" class="font-weight-normal mb-0 text-sm">Repeat Password</label>
                                                            <input type="password" class="form-control form-control-sm" id="password" name="password" autocomplete="new-password">
                                                        </div>
                                                    </div>

                                                    <div class="card-footer p-1">
                                                        <button type="submit" class="btn btn-warning submit-password btn-xs">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="vert-tabs-about-you" role="tabpanel" aria-labelledby="vert-tabs-about-you-tab">
                                            <div class="card card-info mb-0">
                                                <div class="card-header py-1 text-center">
                                                    <h3 class="text-lg mb-0">ABOUT YOU</h3>
                                                </div>

                                                {!! Form::model($profile, ['url' => $urlProfile, 'method' => $methodProfile, 'id' => 'form-profile']) !!}
                                                    <div class="card-body p-1">
                                                        <div class="form-group mb-1">
                                                            <label for="place_of_birth" class="font-weight-normal mb-0 text-sm">Place Of Birth</label>
                                                            <input type="text" class="form-control form-control-sm" id="place_of_birth" name="place_of_birth" value="{{ $profile->place_of_birth }}" placeholder="Isi Tempat Lahir">
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-4">
                                                                <div class="form-group mb-1">
                                                                    <label for="date_of_birth" class="font-weight-normal mb-0 text-sm">Date Of Birth</label>

                                                                    <div class="input-group date" id="date_of_birth" data-target-input="nearest">
                                                                        <input type="text" name="date_of_birth" class="form-control form-control-sm datetimepicker-input" data-target="#date_of_birth" data-toggle="datetimepicker" value="{{ $profile->date_of_birth ? date_format(date_create($profile->date_of_birth), "d/m/Y") : date('d/m/Y') }}" placeholder="Isi Tanggal Lahir" />
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-4">
                                                                <div class="form-group mb-1">
                                                                    <label for="gender" class="font-weight-normal mb-0 text-sm">Gender</label>
                                                                    <select class="form-control form-control-sm select2" id="gender" name="gender" style="width: 100%;">
                                                                        <option value=""></option>
                                                                        <option value="m" {{ $profile->gender == 'm' ? 'selected' : '' }}>Male</option>
                                                                        <option value="w" {{ $profile->gender == 'w' ? 'selected' : '' }}>Female</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-4">
                                                                <div class="form-group mb-1">
                                                                    <label for="hp" class="font-weight-normal mb-0 text-sm">Handphone</label>
                                                                    <input type="text" class="form-control form-control-sm" id="hp" name="hp" value="{{ $profile->hp }}" placeholder="Isi No Handphone">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group mb-1">
                                                            <label for="address" class="font-weight-normal mb-0 text-sm">Address</label>
                                                            <textarea class="form-control form-control-sm" rows="2" id="address" name="address" placeholder="Isi Alamat" style="resize: none;">{{ $profile->address }}</textarea>
                                                        </div>


                                                    </div>

                                                    <div class="card-footer p-1">
                                                        <button type="submit" class="btn btn-warning submit-profile btn-xs">Update</button>
                                                    </div>
                                                {!! Form::close() !!}
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="vert-tabs-image" role="tabpanel" aria-labelledby="vert-tabs-image-tab">
                                            <div class="card card-info mb-0">
                                                <div class="card-header py-1 text-center">
                                                    <h3 class="text-lg mb-0">IMAGE</h3>
                                                </div>

                                                {!! Form::model($image, ['url' => $urlImage, 'method' => $methodImage, 'files' => true, 'id' => 'form-image']) !!}
                                                    <div class="card-body p-1">
                                                        <div class="form-group mb-1">
                                                            <label for="image_profile" class="font-weight-normal mb-0 text-sm">File</label>
                                                            <div class="input-group">
                                                                <div class="custom-file">
                                                                    <input type="file" class="custom-file-input form-control form-control-sm" id="image_profile" name="image_profile" accept=".jpeg, .jpg, .png">
                                                                    <label class="custom-file-label" for="image_profile">Choose file</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="card-footer p-1">
                                                        <button type="submit" class="btn btn-warning submit-image btn-xs">Update</button>
                                                    </div>
                                                {!! Form::close() !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
