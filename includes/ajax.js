
$(document).ready(function(){
	$("#ajax").click(function(evento){
		evento.preventDefault();
		$("#ykBody").load("contenido-ajax.html");
	});
})

