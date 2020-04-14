
var validation = $('#edit_ticket_form').validate({
    errorPlacement: function errorPlacement(error, element) {},
    invalidHandler: function invalidHandler() {
        var errors = validation.errorList;
        var message = [];
        for (var i in errors) {
            var elt = $(errors[i].element);
            message.push(elt.attr('data-field_name'));
        }
        if (message.length > 0) {
            new Noty({
                text: message.join("<br>"),
                layout: 'topCenter',
                timeout: message.length * 1500,
                progressBar: true,
                type: 'error',
                theme: 'bootstrap-v4'
            }).show();
        }
    },
    ignore: [],
    rules: {
        reply_content: {
            required: true
        }
    }
});