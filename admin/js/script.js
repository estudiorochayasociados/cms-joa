$('table').addClass("table-hover");
$('input[type=text]').addClass("form-control");
$('input[type=date]').addClass("form-control");
$('input[type=url]').addClass("form-control");
$('input[type=number]').addClass("form-control");
$('select').addClass("form-control");
$('textarea').addClass("form-control");
$(function() {
    $('[data-toggle="tooltip"]').tooltip();
})
$('.btn-danger').on("click", function(e) {
    e.preventDefault();
    var choice = confirm("¿Estás seguro de eliminar?");
    if (choice) {
        window.location.href = $(this).attr('href');
    }
});
$(".ckeditorTextarea").each(function() {
    CKEDITOR.replace(this, {
        customConfig: 'config.js'
    });
});
$(document).ready(function() {
    $("#myInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("table tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});

function ajaxPost(url) {
    event.preventDefault();
    var form = $("#formAjax").serialize();
    $.ajax({method: "POST", url: url, data: form, dataType: "html",
        beforeSend: function() {
            $("#resultado").html("CARGANDO");
        },
        success: function(result){
            $("#resultado").html(result);
        }});
    event.preventDefault();
}

function ajaxGet(url) {
    console.log(url);
    $.ajax({method: "GET", url: url, dataType: "html",
        beforeSend: function() {
            $("#resultado").html("CARGANDO");
        },
        success: function(result){
            $("#resultado").html(result);
            console.log(result);
        }});
    event.preventDefault();
}

$('.linkModal').click(function(e) {
    e.preventDefault();
    var url = $(this).attr('href');
    var titulo = $(this).attr('data-title');
    $('#contenidoForm').load(url,function(result){
        $('#myModal').modal({show:true});
        $('.modal-title').html(titulo);
        console.log(result);
        e.preventDefault();
    })
});