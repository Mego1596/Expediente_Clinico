$(document).ready(function() {
    $('#seccion_especialidad').hide();
    $('#seccion_emergencia').hide();
    $('#seccion_planta').hide();
     $("#form_rol").change(function () {
        var val = this.value;
        
        if (val == 2){
            $('#seccion_especialidad').show();
		    $('#seccion_emergencia').show();
        } else {
            $('#seccion_especialidad').hide();
		    $('#seccion_emergencia').hide();
		    $('#seccion_planta').hide();
        }
    });
});
 
$("#form_emergencia").change(function(){
	var emergencia = this.value;
	if(emergencia == 1){
		$('#seccion_planta').hide();
		$('#form_planta').val(0);
	}else if(emergencia == 0){
		$('#seccion_planta').show();
		$('#form_planta').val(0);
	}else{
		$('#seccion_planta').hide();
		$('#form_planta').val(0);
	}
});