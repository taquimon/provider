<script>
    $(function() {

        $("#ranking_table").dataTable({
            "bProcessing": true,
            "bInfo": true,
            "bPaginate": true,
            "order": [[ 8, "desc" ]],
            //"sAjaxSource": "<?=site_url('partido/ranking')?>",
            ajax: {
                url: "<?=site_url('partido/ajaxRanking')?>",
                type: "POST",
                data: function(d) {
                    d.disciplina = $("#disciplina").val()
                    d.gestion = $("#gestion").val()
                }

            },
            "columns": [{
                    "data": "club"
                }, {
                    "data": "pj"
                }, {
                    "data": "pg"
                }, {
                    "data": "pe"
                }, {
                    "data": "pp"
                }, {
                    "data": "gf"
                }, {
                    "data": "gc"
                }, {
                    "data": "dg"
                }, {
                    "data": "puntos"
                },

            ]
        });
    });
    
    function filterTable() {
        var club_table = $("#ranking_table").dataTable();
        var dataRankingFilter = {
            disciplina: $("#disciplina").val(),
            gestion: $("#gestion").val(),
        };
        var url = "<?=site_url('partido/ajaxRanking')?>";

        $.ajax({
            url: url,
            data: dataRankingFilter,
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
                gestion = $("#gestion").val();
                var urln = "<?=site_url('partido/ajaxRanking/" + disciplinaid + "/" + gestion +"')?>";
                $("#ranking_table").DataTable().ajax.url(urln);
                $("#ranking_table").DataTable().ajax.reload();
                $("#disciplina").val(disciplinaid);
                $("#gestion").val(gestion);
                $("#titulo").html('Tabla de Posiciones - ' + $("#disciplina option:selected").text());

            },
            error: function() {
                $("#club_message").showFatal();
            }
        });
    }
</script>
<?php
//print_r($this->data);
?>
<div class="grid">
    <div class="row cells12">
        <div class="cell"></div>
        <div class="cell colspan8">
            Gestion: 
            <div class="input-control select">
                    <select name="gestion" id="gestion">                    
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
                    <option value="1">Futbol Senior</option>
                    <option value="4">Futbol Libre</option>
                    <option value="2">Futsala Senior</option>
                    <option value="5">Futsala Libre</option>
                    <option value="6">Basquet Libre</option>
                </select>
            </div>
            <button onclick="filterTable()" class="button rounded bg-amber"><span class="mif-search"></span> Ver</button>
        </div>       
    </div>
    <div class="row cells12">
        <div class="cell colspan2"></div>
        <div class="cell colspan8">
            <h2 id="titulo">Tabla de Posiciones - Futbol Senior</h2>          
        </div>
        <div class="cell colspan2"></div>
    </div>
    <div class="row cells12">
        <div class="cell"></div>
        <div class="cell colspan10">
            <table id="ranking_table" class="dataTable striped border bordered">
                <thead>
                    <tr>
                        <th style="width: 20%">Equipo</th>
                        <th style="width: 10%">PJ</th>
                        <th style="width: 10%">PG</th>
                        <th style="width: 10%">PE</th>
                        <th style="width: 10%">PP</th>
                        <th style="width: 10%">GF</th>
                        <th style="width: 10%">GC</th>
                        <th style="width: 10%">+/-</th>                                                 
                        <th style="width: 20%">Puntos</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div class="cell"></div>
    </div>
</div>