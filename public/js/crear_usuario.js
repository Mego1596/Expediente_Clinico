$(document).ready(function() {
    $('#seccion_especialidades').hide();
    
     $("#form_rol").change(function () {
        var val = this.value;
        
        if (val == 2){
            $('#seccion_especialidades').show();
        } else {
            $('#seccion_especialidades').hide();
        }
    });
});