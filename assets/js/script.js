import $ from 'jquery';

$('#register-form-button').click(function() {
    let name = $('#name').val();
    let email = $('#email').val();
    let password = $('#password').val();
    let repeatPassword = $('#repeat-password').val();
    let data = JSON.stringify({name: name, email: email, password: password, repeatPassword: repeatPassword});
    $.ajax({
        url: '/register',
        type: 'POST',
        contentType: "application/json",
        dataType: 'json',
        data: data,
        success: function (data, status) {
            console.log("DATA", data);
        }
    });
});