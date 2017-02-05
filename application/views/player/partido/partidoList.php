<script>
    var addMode = true;
    var clubid = "Todos";
    fillClubNames();
    var idPartidoGlobal = -1;
    $(function() {

        $("#partido_table").dataTable({
            "bProcessing": true,
            "bInfo": true,
            "bPaginate": true,
            //"sAjaxSource": "<?=site_url('partido/ajaxListPartido')?>",
            ajax: {
                url: "<?=site_url('partido/ajaxListPartido')?>",
                type: "POST",
                data: function(d) {
                    d.disciplina = $("#disciplina").val()
                    d.gestion = $("#gestion").val()
                }

            },
            "columns": [{
                    "data": "equipo1"
                }, {
                    "data": "goles1"
                }, {
                    "data": "puntos1"
                }, {
                    "data": "equipo2"
                }, {
                    "data": "goles2"
                }, {
                    "data": "puntos2"
                }, {
                    "data": "fecha"
                }, {
                    "data": "comments"
                }, {
                    "data": "buttons"
                },

            ]
        });

        $("#datepicker").datepicker({
            inline: false,
            format: "yyyy-mm-dd"
        });


    });

    function addNewPartidoData() {
        var partido_table = $("#partido_table").dataTable();

        var dataPartido = {
            idpartido: idPartidoGlobal,
            disciplina: $("#disciplinaPartido").val(),
            equipo1: $("#clubA").val(),
            equipo2: $("#clubB").val(),
            goles1: $("#golesa").val(),
            goles2: $("#golesb").val(),
            fecha: $("#datepicker").val(),
            hora: $("#hora").val(),
            minuto: $("#minuto").val(),
            puntos1: $("#puntosA").val(),
            puntos2: $("#puntosB").val(),
            comments: $("#comments").val(),
        };

        var url = "<?=site_url('partido/jsonGuardarNuevo')?>";
        if (addMode == false) {
            url = "<?=site_url('partido/jsonGuardarPartido')?>";
        }

        $.ajax({
            url: url,
            data: dataPartido,
            dataType: "json",
            type: 'POST',
            success: function(json) {
                closeDialog("#dialog_new_partido");
                setTimeout(function() {
                    $.Notify({
                        type: 'success',
                        caption: 'Success',
                        content: json.message
                    });
                }, 1000);
                disciplinaid = $("#disciplina").val();
                var urln = "<?=site_url('partido/ajaxListPartido/" + disciplinaid + "')?>";
                $("#partido_table").DataTable().ajax.url(urln);
                $("#partido_table").DataTable().ajax.reload();
                addMode = true;

            },
            error: function() {
                setTimeout(function() {
                    $.Notify({
                        type: 'alert',
                        caption: 'Alert',
                        content: "Error al agregar datos"
                    });
                }, 1000);

            }
        });
    }

    function loadPartidoData(idPartido) {

        $.ajax({
            url: "<?=site_url('partido/ajaxGetPartidoById')?>",
            data: {
                partidoId: idPartido                
            },
            dataType: "json",
            type: 'POST',
            success: function(json) {
                $("#iddisciplina").val(json.disciplina);
                $("#clubA").val(json.equipo1);
                $("#clubB").val(json.equipo2);
                $("#golesa").val(json.goles1);
                $("#golesb").val(json.goles2);
                $("#datepicker").val(json.fecha);
                $("#hora").val(json.hora);
                $("#minuto").val(json.minutos);
                $("#puntosA").val(json.puntos1);
                $("#puntosB").val(json.puntos2);
                $("#comments").val(json.comments);

                showDialog("#dialog_new_partido");
                addMode = false;
                idPartidoGlobal = idPartido;
            },
            error: function() {
                $("#partido_message").showFatal();

            }
        });
    }

    function showDialog(id) {
        var dialog = $(id).data('dialog');
        dialog.open();
    }

    function closeDialog(id) {
        var dialog = $(id).data('dialog');
        dialog.close();
    }

    function fillClubNames() {
        $.ajax({
            url: "<?=site_url('club/ajaxGetClubNames')?>",
            dataType: "json",
            type: 'GET',
            success: function(json) {
                var options = '';
                for (var x = 0; x < json.length; x++) {
                    options += '<option value="' + json[x]['id'] + '">' + json[x]['name'] + '</option>';
                }
                //$('#club').html(options);
                $('#clubA').html(options);
                $('#clubB').html(options);

                //$("#club").select2();
            },
            error: function() {
                $("#partido_message").showFatal();

            }
        });
    }

    function filterTable() {
        var club_table = $("#partido_table").dataTable();
        var dataPartidoFilter = {
            disciplina: $("#disciplina").val(),
            gestion: $("#gestion").val()
        };
        var url = "<?=site_url('partido/ajaxListPartido')?>";

        $.ajax({
            url: url,
            data: dataPartidoFilter,
            dataType: "json",
            type: 'POST',
            success: function(json) {
                setTimeout(function() {
                    $.Notify({
                        type: 'success',
                        caption: 'Success',
                        content: json.message
                    });

                }, 1000);
                disciplinaid = $("#disciplina").val();
                var urln = "<?=site_url('partido/ajaxListPartido/" + disciplinaid + "')?>";
                $("#partido_table").DataTable().ajax.url(urln);
                $("#partido_table").DataTable().ajax.reload();
                $("#disciplina").val(disciplinaid);

            },
            error: function() {
                $("#club_message").showFatal();
            }
        });
    }
</script>

<!-- ui-dialog -->
<div id="dialog_new_partido" title="Agregar Partido" data-role="dialog" id="dialog" class="padding20" data-close-button="true" data-overlay="true" data-overlay-color="op-dark" data-background="bg-cobalt" data-width="700">
    <div id="Partido_message" style="display: none"></div>
    <div class="grid condensed">
        <div class="row cells12">
            <div class="cell"></div>
            <div class="cell colspan10">
                <center>
                    <h4 class="fg-white" id="titleDialog">Datos del Partido</h4>
                </center>
            </div>
            <div class="cell"></div>
        </div>
        <div class="row cells12">
            <div class="cell colspan2">
                <div class="label fg-white">Disciplina:</div>
            </div>
            <div class="cell colspan4">
                <div class="input-control select">
                    <select name="disciplinaPartido" id="disciplinaPartido">
                        <option value="1">Futbol Senior</option>
                        <option value="4">Futbol Libre</option>
                        <option value="2">Futsala Senior</option>
                        <option value="5">Futsala Libre</option>
                        <option value="6">Basquet Libre</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row cells12">
            <div class="cell colspan2">
                <div class="label fg-white">Equipo A:</div>
            </div>
            <div class="cell colspan4">
                <div class="input-control select">
                    <?=form_dropdown( 'Partido.name',array(), '', 'id="clubA"')?>
                </div>
            </div>
            <div class="cell colspan2"><span class="label fg-white">Equipo B:</span>
            </div>
            <div class="cell colspan4">
                <div class="input-control select">
                    <?=form_dropdown( 'Partido.name',array(), '', 'id="clubB"')?>
                </div>
            </div>
        </div>
        <div class="row cells12">
            <div class="cell colspan2"><span class="label fg-white">Goles A:</span>
            </div>
            <div class="cell colspan4">
                <div class="input-control text" data-role="input">
                    <?=form_input(array( 'name'=>'partido.golesa','id' => 'golesa',))?>
                        <button class="button helper-button clear"><span class="mif-cross"></span>
                        </button>
                </div>
            </div>
            <div class="cell colspan2"><span class="label fg-white">Goles B:</span>
            </div>
            <div class="cell colspan4">
                <div class="input-control text" data-role="input">
                    <?=form_input(array( 'name'=>'partido.golesb','id' => 'golesb',))?>
                        <button class="button helper-button clear"><span class="mif-cross"></span>
                        </button>
                </div>
            </div>
        </div>
        <div class="row cells12">
            <div class="cell colspan2"><span class="label fg-white">Fecha:</span>
            </div>
            <div class="cell colspan4">
                <div class="input-control select">
                    <div class="input-control text" data-role="datepicker" data-format="yyyy-mm-dd">
                        <?=form_input(array( 'name'=>'Partido.zona','id' => 'datepicker',))?>
                            <button class="button"><span class="mif-calendar"></span>
                            </button>
                    </div>

                </div>
            </div>
            <div class="cell colspan2"><span class="label fg-white">Hora:</span>
            </div>
            <div class="cell colspan4">
                Hora:
                <select id="hora">
                    <?php for($i=8; $i<18; $i++) { echo "<option value='".$i. "'>{$i}</option>"; } ?>
                </select>
                Min:
                <select id="minuto">
                    <?php for($j=0; $j<59; $j+=5) { echo "<option value='".$j. "'>{$j}</option>"; } ?>
                </select>
            </div>
        </div>
        <div class="row cells12">
            <div class="cell colspan2"><span class="label fg-white">Puntos A:</span>
            </div>
            <div class="cell colspan4">
                <div class="input-control select">
                    <?=form_dropdown( 'Partido.name',array( '0'=>'0','1'=>'1','3'=>'3'), '0', 'id="puntosA"')?>
                </div>
            </div>
            <div class="cell colspan2"><span class="label fg-white">Puntos B:</span>
            </div>
            <div class="cell colspan4">
                <div class="input-control select">
                    <?=form_dropdown( 'Partido.name',array( '0'=>'0','1'=>'1','3'=>'3'), '0', 'id="puntosB"')?>
                </div>
            </div>
        </div>
        <div class="row cells12">
            <div class="cell colspan2"><span class="label fg-white">Comentarios:</span>
            </div>
            <div class="cell colspan4">
                <div class="input-control textarea">
                    <?=form_textarea(array( 'name'=>'partido.comments','id' => 'comments', "cols" => "60",))?>
                </div>
            </div>
        </div>

        <div class="row cells12">
            <div class="cell colspan3">
                <button onclick="addNewPartidoData()" class="button rounded bg-amber"><span class="mif-checkmark"></span> Guardar</button>
            </div>
            <div class="cell colspan3">
                <button onclick="closeDialog('#dialog_new_partido')" class="button rounded bg-amber"><span class="mif-cross"></span> Cancelar</button>
            </div>
        </div>
    </div>

</div>
<!-- ui-dialog -->

<ul class="breadcrumbs">
    <li><a href="#"><span class="icon mif-home"></span></a>
    </li>
    <li><a href="#">Partidos</a>
    </li>
    <li><a href="#">Lista</a>
    </li>
</ul>
<div class="grid">
    <div class="row cells12">
        <div class="cell"></div>
        <div class="cell colspan3">
            Año: 
            <div class="input-control select">
                <select name="gestion" id="gestion">                    
                    <option value="2015">2015</option>
                    <option value="2016" selected="true">2016</option>                    
                </select>
            </div>
        </div>
        <div class="cell colspan5">
            Disciplina:
            <div class="input-control select">
                <select name="disciplina" id="disciplina">
                    <option value="Todos">Todos</option>
                    <option value="1">Futbol Senior</option>
                    <option value="4">Futbol Libre</option>
                    <option value="2">Futsala Senior</option>
                    <option value="5">Futsala Libre</option>
                    <option value="6">Basquet Libre</option>
                </select>
            </div>
            <button onclick="filterTable()" class="button rounded bg-amber"><span class="mif-search"></span> Ver</button>
        </div>
        <div class="cell colspan3">
            <button onclick="showDialog('#dialog_new_partido')" class="button rounded bg-amber"><span class="mif-plus"></span> Agregar Nuevo</button>
        </div>
    </div>
    <div class="row cells12">
        <div class="cell"></div>
        <div class="cell colspan10">
            <table id="partido_table" class="dataTable striped border bordered">
                <thead>
                    <tr>
                        <th style="width: 10%">Equipo A</th>
                        <th style="width: 10%">Goles A</th>
                        <th style="width: 10%">Pts.A</th>
                        <th style="width: 10%">Equipo B</th>
                        <th style="width: 10%">Goles B</th>
                        <th style="width: 10%">Pts.B</th>
                        <th style="width: 10%">Fecha</th>
                        <th style="width: 20%">Comentarios</th>
                        <th style="width: 10%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div class="cell"></div>
    </div>
</div>



