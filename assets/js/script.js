import $ from 'jquery';

$("#user_register_form").submit(function(e) {
    e.preventDefault();
    let form = $(this);
    let url = form.attr('action');
    $('.errors').remove();

    $.ajax({
        type: "POST",
        url: url,
        data: form.serialize(),
        contentType: "application/json",
        dataType: 'json',
        success: function (data, status) {
            if(data.status == 'error') {
                for (var key in data.data) {
                    if (Array.isArray(data.data[key])) {
                        $(form.find('[name*="'+key+'"]')[0]).before('<ul class="errors"><li>'+data.data[key]+'</li></ul>');
                    } else {
                        $(form.find('[name*="'+key+'"]')[0]).before('<ul class="errors"><li>'+data.data[key].first+'</li></ul>');
                    }
                }
            } else {
                form.hide();
                $('#welcome').show();
            }
        }
    });
});
