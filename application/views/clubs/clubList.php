<script>
    var disciplina = "Todos";
    var addMode = true;
    var idClubGlobal = -1;
    $(function() {        
    $("#club_table").dataTable({
            "bProcessing": true,
            "bInfo": true,
            "bPaginate": true,
            //"sAjaxSource": "<?=site_url('club/ajaxListClub/" + disciplina + "')?>",
            ajax:{
                url: "<?=site_url('club/ajaxListClub/')?>",
                type: "POST", 
                data: function(d) {
                    d.disciplina = $("#disciplina").val()
                }

            },
            "columns": [ 
                { "data": "idclub" },
                { "data": "name" },
                { "data": "description" },
                { "data": "disciplinas" },
                { "data": "activo" },
                { "data": "buttons" }                                                    
            ]
        });                                              
                
    });
    
  
    function addNewClubData(){
        var club_table = $("#club_table").dataTable();
        var values = $('input[name="disciplinas[]"]:checked').map(function(){
            return $(this).val();
        }).get().join(",");
        var dataClub = {
                        idClub: idClubGlobal,
                        name: $("#name").val(),
                        description: $("#description").val(),
                        disciplinas: values,
                        gestion: $("#gestion").val(),
            };
        var url = "<?=site_url('club/jsonGuardarNuevo')?>"; 
        if(addMode == false){
            url = "<?=site_url('club/jsonGuardarClub')?>";
        }
            
        $.ajax({
            url: url,
            data: dataClub,
            dataType: "json",
            type: 'POST',
            success: function (json) {                                                                                               
                closeDialog( "#dialog_new_club"); 
                setTimeout(function(){
                    $.Notify({type: 'success', caption: 'Success', content: json.message});
                    
                }, 1000);
                $("#club_table").DataTable().ajax.reload(null,false);                                                
                
            },
            error: function () {                            
                $("#club_message").showFatal();

            }
        });
    }
    
    function loadClubData(idClub){
        
        $.ajax({
            url: "<?=site_url('club/ajaxGetClubById')?>",
            data: { clubId: idClub },
            dataType: "json",
            type: 'POST',
            success: function (json) {                            
                $("#name").val(json.name);
                $("#description").val(json.description);
                $("#gestion").val(json.gestion);
                /*@TODO:Load gestion and disciplinas*/
                showDialog( "#dialog_new_club");
                addMode = false;
                idClubGlobal = idClub;
            },
            error: function () {                            
                $("#club_message").showFatal();

            }
        });
    }
    function filterTable(){
        var club_table = $("#club_table").dataTable();
        var dataClubFilter = {
                        disciplina: $("#disciplina").val(),
            };
        var url = "<?=site_url('club/ajaxListClub')?>";         
            
        $.ajax({
            url: url,
            data: dataClubFilter,
            dataType: "json",
            type: 'POST',
            success: function (json) {
                setTimeout(function(){
                    $.Notify({type: 'success', caption: 'Success', content: json.message});
                    
                }, 1000);
                disciplina = $("#disciplina").val();
                var urln = "<?=site_url('club/ajaxListClub/" + disciplina + "')?>";
                $("#club_table").DataTable().ajax.url(urln);
                $("#club_table").DataTable().ajax.reload();
                
            },
            error: function () {                            
                $("#club_message").showFatal();
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
</script>

<!-- ui-dialog -->
<div id="dialog_new_club" title="Agregar Club" data-role="dialog" id="dialog" class="padding20" data-close-button="true" data-overlay="true" data-overlay-color="op-dark" data-background="bg-darkCobalt">    
<div id="club_message" style="display: none"></div>
<div class="grid">
    <div class="row cells5">
        <div class="cell"></div>
        <div class="cell colspan3"><h3 class="fg-white" id="titleDialog">Datos del Club</h3></div>
        <div class="cell"></div>
    </div>
    <div class="row cells">
        <div class="cell"><span class="label fg-white">Nombre:</span></div>        
        <div class="cell colspan3">
            <div class="input-control text" data-role="input">
            <?=form_input(array('name' => 'club.name','id' => 'name'))?>
            <button class="button helper-button clear"><span class="mif-cross"></span></button>
            </div>            
        </div>
    </div>
    <div class="row cells">
        <div class="cell"><span class="label fg-white">Gestion:</span></div>
        <div class="cell colspan3">
            <div class="input-control select">
                <select name="gestion" id="gestion">                    
                    <option value="2015">2015</option>
                    <option value="2016">2016</option>
                    <option value="2017">2017</option>
                    <option value="2018">2018</option>
                    <option value="2019">2019</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row cells">                     
        <div class="cell"><span class="label fg-white">Descripcion:</span></div>
        <div class="cell">
            <div class="input-control textarea">
                <?=form_textarea(array('name' => 'club.description','id' => 'description', "cols" => "60","rows"=>"5"))?> 
            </div>                
        </div>
    </div>
    <div class="row cells">
        <div class="cell"><span class="label fg-white">Disciplina</span></div> 
        <div class="cell">             
            <label class="input-control checkbox small-check">
                <input type="checkbox" name="disciplinas[]" value="1">
                <span class="check"></span>
                <span class="caption fg-white">Futbol Senior</span>
            </label>
            <label class="input-control checkbox small-check">
                <input type="checkbox" name="disciplinas[]" value="4">
                <span class="check"></span>
                <span class="caption fg-white">Futbol Libre</span>
            </label> 
            <label class="input-control checkbox small-check">
                <input type="checkbox" name="disciplinas[]" value="2">
                <span class="check"></span>
                <span class="caption fg-white">Futsala Senior</span>
            </label> 
            <label class="input-control checkbox small-check">
                <input type="checkbox" name="disciplinas[]" value="5">
                <span class="check"></span>
                <span class="caption fg-white">Futsal Libre</span>
            </label> 
            <label class="input-control checkbox small-check">
                <input type="checkbox" name="disciplinas[]" value="6">
                <span class="check"></span>
                <span class="caption fg-white">Basquet</span>
            </label> 
        </div>
        <div class="cell">
        </div>
    </div>            
    <div class="row cells4">        
        <div class="cell colspan2">            
            <button onclick="addNewClubData()" class="button rounded bg-amber"><span class="mif-checkmark"></span> Guardar</button>
        </div>
        <div class="cell colspan2">
            <button onclick="closeDialog('#dialog_new_club')" class="button rounded bg-amber"><span class="mif-cross"></span> Cancelar</button>
        </div>
    </div>
</div>
	
</div>
<!-- ui-dialog -->

<ul class="breadcrumbs">
    <li><a href="#"><span class="icon mif-home"></span></a></li>
    <li><a href="#">Club</a></li>
    <li><a href="#">Lista</a></li>    
</ul>
<div class="grid">
    <div class="row cells12">
        <div class="cell"></div>
        <div class="cell colspan8">
            Gestion:
            <div class="input-control select">
                <select name="gestion" id="gestion1">                    
                    <option value="2015">2015</option>
                    <option value="2016">2016</option>
                    <option value="2017">2017</option>
                    <option value="2018">2018</option>
                    <option value="2019">2019</option>
                </select>
            </div> 
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
            <button onclick="showDialog('#dialog_new_club')" class="button rounded bg-amber"><span class="mif-plus"></span> Agregar Nuevo</button>
        </div>        
    </div>
    <div class="row cells12">
        <div class="cell"></div>
        <div class="cell colspan10">
            <table id="club_table" class="dataTable striped border bordered">
                <thead>
                    <tr>
                        <th style="width: 10%">Id</th>
                        <th style="width: 20%">Nombre</th>
                        <th style="width: 30%">Descripcion</th>
                        <th style="width: 20%">Disciplinas</th>
                        <th style="width: 10%">Activo</th>
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

