import $ from 'jquery';

$("#user_register_form").submit(function(e) {
    //e.preventDefault(); testing without ajax
    let form = $(this);
    let url = form.attr('action');

    $.ajax({
        type: "POST",
        url: url,
        data: form.serialize(),
        contentType: "application/json",
        dataType: 'json',
        success: function (data, status) {
            console.log("DATA", data);
        }
    });
});
