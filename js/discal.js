function loadCategoria(url, dropdown, optionSelected) {
    $.ajax({
        url: url,
        dataType: "json",
        type: 'POST',
        success: function(json) {
            productData = json;
            var options = '';
            for (var x = 0; x < json.length; x++) {
                options += '<option value="' + json[x].idCategoriaProducto + '" ';
                if (optionSelected == json[x].idCategoriaProducto) {
                    options += "selected";
                }
                options += '>' + json[x].nombre + '</option>';
            }

            $('#' + dropdown).html(options);
            $('#' + dropdown).selectpicker('refresh');
        },
        error: function() {
            var n = noty({
                type: "error",
                text: "Error al cargar empresas",
                animation: {
                    open: {
                        height: 'toggle'
                    },
                    close: {
                        height: 'toggle'
                    },
                    easing: 'swing',
                    speed: 500 // opening & closing animation speed
                }
            });

        }
    });
}

function fillVendedor(url, dropdown, optionSelected) {
    $.ajax({
        url: url,
        dataType: "json",
        type: 'GET',
        success: function(json) {
            var options = '';
            for (var x = 0; x < json.length; x++) {
                options += '<option value="' + json[x].idVendedor + '">' + json[x].nombres + ' ' + json[x].apellidos + '</option>';
            }

            $('#' + dropdown).html(options);
            if(optionSelected != "") {
                $('#' + dropdown).val(optionSelected);
            }            
            $('#' + dropdown).selectpicker('refresh');

        },
        error: function() {}
    });
}