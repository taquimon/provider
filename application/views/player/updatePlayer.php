<script type="text/javascript">
    var clubid = "Todos";
    fillClubNames();
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

    function addNewplayerData(){
        //var player_table = $("#player_table").dataTable();
        var radio_button_value;

           if ($("input[name='docs']:checked").length > 0){
               radio_button_value = $('input:radio[name=docs]:checked').val();
           }
        var dataplayer = {
                        //idplayer: idPlayerGlobal,
                        name: $("#name").val(),
                        last: $("#last").val(),
                        ci: $("#ci").val(),
                        fechanacimiento : $("#datepicker").val(),
                        club: $("#club").val(),
                        //categoria: $("#categoria").val(),
                        documento : radio_button_value,
                        disciplina: $("#disciplina").val(),
                        parentesco: $("#parentesco").val(),
                        notas: $("#notas").val(),
            };
        
        var url = "<?=site_url('player/jsonGuardarNuevo')?>";        
            
        $.ajax({
            url: url,
            data: dataplayer,
            dataType: "json",
            type: 'POST',
            success: function (json) {                                                                                               
                //closeDialog( "#dialog_new_player"); 
                setTimeout(function(){
                    $.Notify({type: 'success', caption: 'Success', content: json.message});                    
                }, 1000);
                clubid = $("#clublist").val();
                var urln = "<?=site_url('player/ajaxListPlayer/" + clubid + "')?>";
                //$("#player_table").DataTable().ajax.url(urln);
                //$("#player_table").DataTable().ajax.reload();
                //addMode = true;
                
            },
            error: function () {                            
                setTimeout(function(){
                    $.Notify({type: 'alert', caption: 'Alert', content: "Error al agregar datos"});                    
                }, 1000);

            }
        });
    }
</script>
<div class="grid condensed padding20">
    <div class="row cells12">
        <div class="cell"></div>
        <div class="cell colspan10"><center><h2 class="fg-darkCobalt">Datos del Jugador</h2></center></div>
        <div class="cell"></div>
    </div>
    <div class="row cells12">
        <div class="cell colspan2"><div class="label fg-darkCobalt">Nombre(s):</div></div>        
        <div class="cell colspan4">
            <div class="input-control text info" data-role="input">
            <?=form_input(array('name' => 'player.name','id' => 'name','size' => '25',))?>
            <button class="button helper-button clear"><span class="mif-cross"></span></button>
        </div>
        </div>
        <div class="cell colspan2"><span class="label fg-darkCobalt">Apellidos</span></div>
        <div class="cell colspan4">
            <div class="input-control text info" data-role="input">
            <?=form_input(array('name' => 'player.last','id' => 'last','size' => '25',))?>
            <button class="button helper-button clear"><span class="mif-cross"></span></button>
            </div>
        </div>
    </div>
    <div class="row cells12">                     
        <div class="cell colspan2"><span class="label fg-darkCobalt">CI:</span></div>
        <div class="cell colspan4">
            <div class="input-control text info"  data-role="input">
                <?=form_input(array('name' => 'player.ci','id' => 'ci',))?> 
            <button class="button helper-button clear"><span class="mif-cross"></span></button>
            </div>                            
        </div>
        <div class="cell colspan2"><span class="label fg-darkCobalt">Fecha Nac.:</span></div>                
        <div class="cell colspan4">       
            <div class="input-control text info" data-role="datepicker" data-format="yyyy-mm-dd">
            <?=form_input(array('name' => 'player.zona','id' => 'datepicker',))?>  
            <button class="button"><span class="mif-calendar"></span></button>
            </div>
        </div>
    </div>
    <div class="row cells12">                     
        <div class="cell colspan2"><span class="label fg-darkCobalt">Disciplina:</span></div>
        <div class="cell colspan4">
            <div class="input-control select info" data-role="input">
                <?=form_dropdown('disciplina', array("1"=>"Futbol Senior","2"=>"Futsala Senior", "4"=>"FutBol Libre","5"=>"Futsala Libre","6" => "Basquet Libre") ,'Futbol Senior','id="disciplina"')?>
                </div>                
            </div>
        <div class="cell colspan2"><span class="label fg-darkCobalt">Parentesco:</span></div>
        <div class="cell colspan4">
            <div class="input-control select info">
            <?=form_dropdown('player.parentesco',array('NINGUNO'=>'NINGUNO','HUANUNEÑO'=>'HUANUNEÑO', 'HIJO'=>'HIJO', 'ESPOSO'=>'ESPOSO','OTRO'=>'OTRO'),'HUANUNEÑO', 'id="parentesco"')?>
            </div>
        </div>
    </div>
    <div class="row cells12">
        <div class="cell colspan2"><span class="label fg-darkCobalt">Club:</span></div>
        <div class="cell colspan4">        
            <div class="input-control select info">
                <?=form_dropdown('player.name',array(),'', 'id="club"')?> 
            </div>
            
        </div>
        <div class="cell colspan2"><span class="label fg-darkCobalt">Documentos:</span></div>
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
        <div class="cell colspan2"><span class="label fg-darkCobalt">Notas.:</span></div>
        <div class="cell colspan10">
            <div class="input-control textarea info">
            <?=form_textarea(array('name'=>'notas','id'=>'notas','cols'=>'100'))?>
        </div>
    </div>
    </div>

    <div class="row cells12">        
        <div class="cell colspan12">         
            <button onclick="addNewplayerData()" class="button rounded bg-amber"><span class="mif-checkmark"></span> Guardar</button>        
            <!-- <button onclick="closeDialog('#dialog_new_player')" class="button rounded bg-amber"><span class="mif-cross"></span> Cancelar</button> -->
        </div>
    </div>
</div>