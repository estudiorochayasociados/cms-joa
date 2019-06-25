<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1>Exportar productos a MercadoLibre
                </h1>
            </div>
            <div class="col-md-12 text-center">
                <button id="btnEx1" class="btn btn-success " onclick="exportMLType(1);">TODOS (EDITAR/AGREGAR)</button>
                <button id="btnEx2" class="btn btn-success " onclick="exportMLType(2);">SOLO PRODUCTOS NO VINCULADOS</button>
                <button id="btnEx3" class="btn btn-success " onclick="exportMLType(3);">SOLO PRODUCTOS VINCULADOS</button>
            </div>
        </div>
        <hr>
        <div id="info">

        </div>
    </div>
    <div class="col-md-12">
        <div class="row">
            <div id="listS" class="col-md-6">

            </div>
            <div id="listE" class="col-md-6">

            </div>
        </div>
    </div>
</div>

<div id="modalS" class="modal fade mt-120" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center">
                    <i class="fa fa-check-circle fs-90 " style="color:green"></i>
                    <br>
                    <div class="text-uppercase text-center">
                        <p class="fs-18 mt-10" style="margin:auto;width: 250px" id="textS"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="modalE" class="modal fade mt-120" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center">
                    <i class="fa fa-exclamation-circle fs-90 " style="color:red"></i>
                    <br>
                    <span class="text-uppercase fs-16" id="error"></span>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function delay() {
        return new Promise(resolve => setTimeout(resolve, 3000));
    }

    function exportMLType(type) {
        $.ajax({
            url: "<?=URLSITE?>/curl/ml/list.php",
            type: "POST",
            data: {type: type},
            beforeSend: function () {
                $('#btnEx1').attr("disabled", true);
                $('#btnEx2').attr("disabled", true);
                $('#btnEx3').attr("disabled", true);
                switch (type) {
                    default:
                        $('#btnEx1').addClass("ld-ext-right running");
                        $('#btnEx1').append("<div class='ld ld-ring ld-spin'></div>");
                        break;
                    case 2:
                        $('#btnEx2').addClass("ld-ext-right running");
                        $('#btnEx2').append("<div class='ld ld-ring ld-spin'></div>");
                        break;
                    case 3:
                        $('#btnEx3').addClass("ld-ext-right running");
                        $('#btnEx3').append("<div class='ld ld-ring ld-spin'></div>");
                        break;
                }
            },
            success: async function (data) {
                switch (type) {
                    default:
                        $('#btnEx1').html("TODOS (EDITAR/AGREGAR) ");
                        $('#btnEx1').removeClass("ld-ext-right running");
                        break;
                    case 2:
                        $('#btnEx2').html("SOLO PRODUCTOS NO VINCULADOS ");
                        $('#btnEx2').removeClass("ld-ext-right running");
                        break;
                    case 3:
                        $('#btnEx3').html("SOLO PRODUCTOS VINCULADOS ");
                        $('#btnEx3').removeClass("ld-ext-right running");
                        break;
                }
                data = JSON.parse(data);
                if (data['status']) {
                    var increment = (1 * 100) / data['products'].length;
                    var total = data['products'].length;
                    var estimated = (4 * total) / 60;
                    var estimate;
                    var estimateM;
                    var m = Math.round(estimated % 3600 / 60);
                    if (m > 1) {
                        estimate = " (~" + m + " Horas)";
                    } else {
                        estimateM = Math.round(estimated);
                        estimate = " (~" + estimateM + " Minutos)";
                    }
                    $('#info').append("<h5 class='text-center'>Los productos se estan subiendo/actualizando en MercadoLibre, por favor aguarde y no cierre esta página.</h5>");
                    $('#info').append("<div class='text-center ld-ext-right running fs-17'><strong>Producto: </strong><strong id='productSingle'>0</strong><strong id='products'>/" + total + " </strong><label> " + estimate + "</label><div class='ld ld-ring ld-spin'></div></div>");
                    $('#info').append("<progress id='progress-bar' class='prb' max='100' value='0'></progress>");

                    for (var i = 0; i < total; i++) {
                        //await sendML(data['products'][i], increment);
                        await delay();
                    }

                } else {
                    $('#error').html('');
                    $('#error').append('Ocurrió un error, por favor recargue la página nuevamente.');
                    $('#modalE').modal('toggle');
                }
            },
            error: function () {
                //alert('Error occured');
            }
        });
    }


    function sendML(product, increment) {
        $.ajax({
            url: "<?=URLSITE?>/curl/ml/export.php",
            type: "POST",
            data: {product: product},
            success: function (data) {
                console.log(data);
                data = JSON.parse(data);
                if (data['response']) {
                    $('#progress-bar').val($('#progress-bar').val() + increment);

                    $('#productSingle').html(parseInt($('#productSingle').text()) + 1);
                    if (data['status']) {
                        $('#listS').append("<div class='alert alert-success'>" + data['text'] + "</div>");
                    } else {
                        $('#listE').append("<div class='alert alert-danger'>" + data['text'] + "</div>");
                    }
                    if ($('#progress-bar').val() >= 100) {
                        $('#textS').html('');
                        $('#textS').append("Carga de productos finalizada.");
                        $('#info').html('');
                        $('#modalS').modal('toggle');
                    }
                } else {
                    $('#error').html('');
                    $('#error').append('Vincule su cuenta de Mercado Libre en la esquina superior derecha.');
                    $('#modalE').modal('toggle');
                }
            },
            error: function () {
                //alert('Error occured');
            }
        });
    }
</script>
