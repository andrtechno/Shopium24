$(function () {
    if ($('.form-group-auto .form-control').val() === '') {
        $('.form-group-auto .form-control').closest('.form-group-auto').addClass('focused');
    } else {
        $('.form-group-auto .form-control').closest('.form-group-auto').addClass('focused');
    }
    $('.form-group-auto .form-control').on('focus', this, function () {
        $(this).closest('.form-group-auto').addClass('focused');
    });
    $('.form-group-auto .form-control').on('blur', this, function () {
        if ($(this).val() === '') {
            $(this).closest('.form-group-auto').removeClass('focused');
        }
    });
});