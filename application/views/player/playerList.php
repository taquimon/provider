<script>
    var addMode = true;
    var clubid = "Todos";
    fillClubNames();
    var idPlayerGlobal = -1;
    $(function() {        
    $("#player_table").dataTable({
            "bProcessing": true,
            "bInfo": true,
            "bPaginate": true,            
            ajax:{
                url: "<?=site_url('product/ajaxListProduct')?>",
                type: "POST",
                data: function(d) {
                    d.club = clubid;
                }

            },
            "columns": [ 
                { "data": "idjugador" },
                { "data": "name" },
                { "data": "last" },
                { "data": "ci" },
                { "data": "fechanacimiento" },                               
                { "data": "idclub" },
                { "data": "disciplina" },
                { "data": "parentesco" }, 
                { "data": "documento" },
                { "data": "notas" },
                { "data": "kardex" },
                { "data": "buttons" },
                
            ]
        });                                              
                
        $( "#datepicker" ).datepicker({
            inline: false,
            format: "yyyy-mm-dd"
        });
    });
        
    function addNewplayerData(){
        var player_table = $("#player_table").dataTable();
        var radio_button_value;

           if ($("input[name='docs']:checked").length > 0){
               radio_button_value = $('input:radio[name=docs]:checked').val();
           }
        var dataplayer = {
                        idplayer: idPlayerGlobal,
                        name: $("#name").val(),
                        last: $("#last").val(),
                        ci: $("#ci").val(),
                        fechanacimiento : $("#datepicker").val(),
                        club: $("#club").val(),
                        categoria: $("#categoria").val(),
                        documento : radio_button_value,
                        disciplina: $("#disciplina").val(),
                        parentesco: $("#parentesco").val(),
                        notas: $("#notas").val(),
            };
        
        var url = "<?=site_url('player/jsonGuardarNuevo')?>";
        if(addMode == false){
            url = "<?=site_url('player/jsonGuardarPlayer')?>";
        }
            
        $.ajax({
            url: url,
            data: dataplayer,
            dataType: "json",
            type: 'POST',
            success: function (json) {                                                                                               
                closeDialog( "#dialog_new_player"); 
                setTimeout(function(){
                    $.Notify({type: 'success', caption: 'Success', content: json.message});                    
                }, 1000);
                clubid = $("#clublist").val();
                var urln = "<?=site_url('player/ajaxListPlayer/" + clubid + "')?>";
                $("#player_table").DataTable().ajax.url(urln);
                $("#player_table").DataTable().ajax.reload();
                addMode = true;
                
            },
            error: function () {                            
                setTimeout(function(){
                    $.Notify({type: 'alert', caption: 'Alert', content: "Error al agregar datos"});                    
                }, 1000);

            }
        });
    }
    
    function loadPlayerData(idPlayer){
        //fillClubNames();
        $.ajax({
            url: "<?=site_url('player/ajaxGetPlayerById')?>",
            data: { playerId: idPlayer },
            dataType: "json",
            type: 'POST',
            success: function (json) {                            
                $("#name").val(json.name);
                $("#last").val(json.last);
                $("#ci").val(json.ci);
                $("#datepicker").val(json.fechanacimiento);
                $("#club").val(json.idclub);
                /*$("#club option").each(function() {
                    if($(this).text() == json.idclub) {
                        $(this).attr('selected', 'selected');
                    }
                });*/
                $("#parentesco").val(json.parentesco);
                $("#disciplina").val(json.disciplina);
                $("#notas").val(json.notas);
                //$("#kardex").val(json.kardex);                        
                showDialog( "#dialog_new_player");
                addMode = false;
                idPlayerGlobal = idPlayer;
            },
            error: function () {                            
                $("#player_message").showFatal();

            }
        });
    }
    function loadKardexData(idPlayer){
        //fillClubNames();
        $.ajax({
            url: "<?=site_url('player/ajaxGetPlayerById')?>",
            data: { playerId: idPlayer },
            dataType: "json",
            type: 'POST',
            success: function (json) {                            
                $("#name").val(json.name);
                $("#last").val(json.last);
                $("#ci").val(json.ci);
                $("#datepicker").val(json.fechanacimiento);
                $("#club").val(json.idclub);                
                $("#parentesco").val(json.parentesco);
                $("#disciplina").val(json.disciplina);
                if(json.kardex != null){
                    $("#kardex").attr('src',"<?=site_url()?>" + json.kardex);    
                }
                else{
                    $("#kardex").attr('src',"");       
                }

                        
                showDialog( "#dialog_kardex_player");                
            },
            error: function () {
                $("#player_message").showFatal();

            }
        });
    }
    
    function showDialog(id){
        var dialog = $(id).data('dialog');
        dialog.open();
    }

    function closeDialog(id){
            var dialog = $(id).data('dialog');
            dialog.close();        
    }
    
    function fillClubNames(){
        $.ajax({
            url: "<?=site_url('club/ajaxGetClubNames')?>",            
            dataType: "json",
            type: 'GET',
            success: function (json) {                            
                var options = '<option value="Todos">Todos</option>';
                for (var x = 0; x < json.length; x++) {
                    options += '<option value="' + json[x]['id'] + '">' + json[x]['name'] + '</option>';
                }
                $('#club').html(options);
                $('#clublist').html(options);
                //$("#club").select2();
            },
            error: function () {                            
                $("#player_message").showFatal();

            }
        });
    }
    function filterTable(){
        var club_table = $("#player_table").dataTable();
        var dataPlayerFilter = {
            club: $("#clublist").val(),
        };
        var url = "<?=site_url('player/ajaxListPlayer')?>";

        $.ajax({
            url: url,
            data: dataPlayerFilter,
            dataType: "json",
            type: 'POST',
            success: function (json) {
                setTimeout(function(){
                    $.Notify({type: 'success', caption: 'Success', content: json.message});

                }, 1000);
                clubid = $("#clublist").val();
                var urln = "<?=site_url('player/ajaxListPlayer/" + clubid + "')?>";
                $("#player_table").DataTable().ajax.url(urln);
                $("#player_table").DataTable().ajax.reload();
                $("#clublist").val(clubid);

            },
            error: function () {
                $("#club_message").showFatal();
            }
        });
    }
</script>
<!-- ui-dialog kardex -->                       
<div id="dialog_kardex_player" 
    title="Kardex" data-role="dialog" 
    class="padding20" data-close-button="true" 
    data-windows-style="true" data-overlay="true" 
    data-background="bg-darkCobalt" data-color="fg-white"
    data-overlay="true" data-overlay-color="op-dark">
    <div class="grid condensed" border=1>
        <div class="row cells12">
            <div class="cell"></div>
            <div class="cell colspan10"><center><h4 class="fg-white" id="titleDialog">Kardex del Jugador</h4></center></div>
            <div class="cell"></div>
        </div>
        <div class="row cells12">
            <div class="cell"></div>
            <img src="" id="kardex">
            <div class="cell"></div>
        </div>
    </div>    
</div>    

<!-- ui-dialog -->                       
<div id="dialog_new_player" 
    title="Agregar player" data-role="dialog"
    class="padding20" data-close-button="true" 
    data-windows-style="true" data-overlay="true" 
    data-background="bg-darkCobalt" data-color="fg-white"
    data-overlay="true" data-overlay-color="op-dark">    
<div id="player_message" style="display: none"></div>
<div class="grid condensed" border=1>
    <div class="row cells12">
        <div class="cell"></div>
        <div class="cell colspan10"><center><h4 class="fg-white" id="titleDialog">Datos del Jugador</h4></center></div>
        <div class="cell"></div>
    </div>
    <div class="row cells12">
        <div class="cell colspan2"><div class="label fg-white">Nombre(s):</div></div>        
        <div class="cell colspan4">
            <div class="input-control text info" data-role="input">
            <?=form_input(array('name' => 'player.name','id' => 'name','size' => '25',))?>
            <!-- <button class="button helper-button clear"><span class="mif-cross"></span></button> -->
        </div>
        </div>
        <div class="cell colspan2"><span class="label fg-white">Apellidos</span></div>
        <div class="cell colspan4">
            <div class="input-control text" data-role="input">
            <?=form_input(array('name' => 'player.last','id' => 'last','size' => '40',))?>
            <!-- <button class="button helper-button clear"><span class="mif-cross"></span></button> -->
            </div>
        </div>
    </div>
    <div class="row cells12">                     
        <div class="cell colspan2"><span class="label fg-white">CI:</span></div>
        <div class="cell colspan4">
            <div class="input-control text"  data-role="input">
                <?=form_input(array('name' => 'player.ci','id' => 'ci',))?> 
            <!-- <button class="button helper-button clear"><span class="mif-cross"></span></button> -->
            </div>                            
        </div>
        <div class="cell colspan2"><span class="label fg-white">Fecha Nac.:</span></div>                
        <div class="cell colspan4">       
            <div class="input-control text" data-role="datepicker" data-format="yyyy-mm-dd">
            <?=form_input(array('name' => 'player.zona','id' => 'datepicker',))?>  
            <button class="button"><span class="mif-calendar"></span></button>
            </div>
        </div>
    </div>
    <div class="row cells12">                     
        <div class="cell colspan2"><span class="label fg-white">Club:</span></div>
        <div class="cell colspan4">
            <div class="input-control select">
                <?=form_dropdown('player.name',array(),'', 'id="club"')?> 
            </div>                
        </div>
        <div class="cell colspan2"><span class="label fg-white">Disciplina:</span></div>
        <div class="cell colspan4">
            <div class="input-control select" data-role="input">
            <?=form_dropdown('disciplina', array("1"=>"Futbol Senior","2"=>"Futsala Senior", "4"=>"FutBol Libre","5"=>"Futsala Libre","6" => "Basquet Libre") ,'Futbol Senior','id="disciplina"')?>
            </div>
        </div>
    </div>
    <div class="row cells12">
        <div class="cell colspan2"><span class="label fg-white">Parentesco Hab.:</span></div>
        <div class="cell colspan4">
            <div class="input-control select">
            <?=form_dropdown('player.parentesco',array('NINGUNO'=>'NINGUNO','HUANUNEÑO'=>'HUANUNEÑO', 'HIJO'=>'HIJO', 'ESPOSO'=>'ESPOSO','OTRO'=>'OTRO'),'HUANUNEÑO', 'id="parentesco"')?>
            </div>
        </div>
        <div class="cell colspan2"><span class="label fg-white">Documentos:</span></div>
        <div class="cell colspan4">
            <label class="input-control radio small-check">
                <?=form_radio(array('name'=>'docs','id'=>'doc_si','value'=>'SI'))?>
                <span class="check"></span>
                <span class="caption">SI</span>
            </label>
            <label class="input-control radio small-check">
                <?=form_radio(array('name'=>'docs','id'=>'doc_no','value'=>'NO','checked'=>TRUE))
?>
                <span class="check"></span>
                <span class="caption">NO</span>
            </label>
        </div>
    </div>

    <div class="row cells12">
        <div class="cell colspan2"><span class="label fg-white">Notas.:</span></div>
        <div class="cell colspan10">
            <div class="input-control textarea">
            <?=form_textarea(array('name'=>'notas','id'=>'notas','cols'=>'100'))?>
        </div>
    </div>
    </div>

    <div class="row cells12">        
        <div class="cell colspan12">         
            <button onclick="addNewplayerData()" class="button rounded bg-amber"><span class="mif-checkmark"></span> Guardar</button>        
            <button onclick="closeDialog('#dialog_new_player')" class="button rounded bg-amber"><span class="mif-cross"></span> Cancelar</button>
        </div>
    </div>
</div>
	
</div>
<!-- ui-dialog -->

<ul class="breadcrumbs">
    <li><a href="#"><span class="icon mif-home"></span></a></li>
    <li><a href="#">Jugadores</a></li>
    <li><a href="#">Lista</a></li>    
</ul>
<div class="grid">
    <div class="row cells12">
        <div class="cell"></div>
        <div class="cell colspan4">
            Elegir Club: <div class="input-control select">
                <?=form_dropdown('player.name',array(),'', 'id="clublist"')?>
            </div>
            <button onclick="filterTable()" class="button rounded bg-amber"><span class="mif-search"></span> Ver</button>
            </div>
        <div class="cell  colspan4"></div>
        <div class="cell  colspan2">
            <a href="<?=base_url().'player/updatePlayer'?>" class="button rounded bg-amber"><span class="mif-plus"></span> Agregar Nuevo</a>
        </div>

    </div>
    <div class="row cells12">
            <table id="player_table" class="dataTable striped border bordered">
                <thead>
                    <tr>
                        <th style="width: 5%">Id</th>
                        <th style="width: 10%">Nombre</th>
                        <th style="width: 10%">Apellidos</th>
                        <th style="width: 10%">CI</th>
                        <th style="width: 10%">Fecha Nac.</th>                        
                        <th style="width: 10%">Club</th>
                        <th style="width: 10%">Disciplina</th>
                        <th style="width: 10%">Parentesco</th>
                        <th style="width: 10%">Documentos</th>
                        <th style="width: 5%">Notas</th>
                        <th style="width: 5%">Kardex</th>
                        <th style="width: 5%">Actions</th>
                    </tr>
                </thead>                
            </table>
        </div>
        <div class="cell"></div>
    </div>
</div>



