$('.date').mask("00/00/0000", {clearIfNotMatch: true, placeholder: "__ /__ /____"});
$('.time').mask("00:00", {clearIfNotMatch: true, placeholder: "__ : __"});
$.datepicker.setDefaults(
    $.extend(
        {'dateFormat':'dd/mm/yy'},
        $.datepicker.regional['pt-BR']
    )
);
$('.datepicker').datepicker();
$("textarea").editor({uiLibrary: 'bootstrap'});