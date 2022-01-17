const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 4000,
    timerProgressBar: true,
    onOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

alert_toast = (icon, title) => {
    Toast.fire({
        icon: icon,
        title: `&nbsp;&nbsp; ${title}`
    })
}

form_validation = (data) => {
    $.each(data.errors, function (key, value) {
        var elem = $('#'+key).closest('.form-group .form-control');
        if (elem.hasClass("select2-hidden-accessible")) {
            $('#'+key).closest('.form-group').find('.select2-container .selection .select2-selection').addClass('error-select2');
            $('#'+key).closest('.form-group .form-control').addClass('is-invalid');
            $('#'+key).closest('.form-group').append('<span id="'+key+'-error" class="error invalid-feedback">'+value+'</span>');
        } else {
            if ($('#'+key).hasClass('date')) {
                $('#'+key).find('.form-control').addClass('is-invalid');
                $('#'+key).append('<span id="'+key+'-error" class="error invalid-feedback">'+value+'</span>');
            } else if ($('#'+key).hasClass('custom-file-input')) {
                $('#'+key).closest('.form-group .form-control').addClass('is-invalid');
                $('#'+key).closest('.form-group').append('<span id="'+key+'-error" class="error invalid-feedback" style="display: inline;">'+value+'</span>');
            } else {
                $('#'+key).closest('.form-group .form-control').addClass('is-invalid');
                $('#'+key).closest('.form-group').append('<span id="'+key+'-error" class="error invalid-feedback">'+value+'</span>');
            }
        }
    });
}

$('.logout').on('click', function(e) {
    e.preventDefault();

    Swal.fire({
        icon: 'warning',
        title: 'Apakah kamu yakin log out ?',
        html: '<span class="text-sm">Klik "Log Out" dibawah, jika kamu yakin ingin log out.</span>',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Log Out',
        focusCancel: true,
        width: '30%',
        position: 'top'
        // padding: '5px',
    }).then((result) => {
        if (result.value) {
            document.getElementById('logout-form').submit();
        }
    })
})
