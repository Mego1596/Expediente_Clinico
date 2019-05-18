$(document).ready(function() {
    if ($("#form_rol").val() == 2)
    {
        $('#seccion_especialidad').show();
        $('#seccion_emergencia').show();
        $('#seccion_planta').show();
    }
    else
    {
        $('#seccion_especialidad').hide();
        $('#seccion_emergencia').hide();
        $('#seccion_planta').hide();
    }
    
    
    $("#form_rol").change(function () {
        var val = this.value;
        
        if (val == 2){
            $('#seccion_especialidad').show();
            $('#seccion_emergencia').show();
            $('#seccion_planta').show();
        } else {
            $('#seccion_especialidad').hide();
            $('#seccion_emergencia').hide();
            $('#seccion_planta').hide();
        }
    });
});