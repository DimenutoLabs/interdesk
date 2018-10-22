var validation = $('#new_ticket_form').validate({
    errorPlacement: function (error, element) {
    },
    invalidHandler: function () {
        var errors = validation.errorList;
        var message = [];
        for (var i in errors) {
            var elt = $(errors[i].element);
            message.push(elt.attr('data-field_name'));
        }
        if ( message.length > 0 ) {
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
        small_title : {
            required : true
        },
        prior : {
            required : true
        },
        department : {
            required : function(element) {
                return $('#assigned_to').val() == "";
            }
        },
        content : {
            required : true
        }
    }
});