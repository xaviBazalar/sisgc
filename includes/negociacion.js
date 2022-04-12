function activarcompromiso(tipo) {
	var linea = new String(document.frmDatos.resultado.value);
	var lista = linea.substr(0, 4);
	if (lista == "1000" || lista == "1005" || lista == "1006" || lista == "1021" || lista == "1022") {
		var targetElement = document.getElementById("resultados");
		targetElement.style.visibility = "visible";
		targetElement.style.position = "relative";
		if (tipo == "N") {
			document.frmDatos.fechacompromiso.value = "{/literal}{$negociaciones[0].gesfeccomp}{literal}";
			document.frmDatos.importecompromiso.value = "{/literal}{$negociaciones[0].gesimpcomp}{literal}";
		}
		else {
			if (lista == "1000") {
				var url = "includes/ws/buscar_compromiso.php";
				var pars = "ctaid=" + document.frmDatos.cta1.value;
				var myAjax = new Ajax.Request( url, {method: "get", parameters: pars, onComplete: mostrar_compromiso} );
			}
		}
	}
	else {
		var targetElement = document.getElementById("resultados");
		targetElement.style.visibility = "hidden";
		targetElement.style.position = "absolute";
		if (document.frmDatos.importecompromiso.value == "") {
			document.frmDatos.importecompromiso.value = 0; }
	}
}

function buscar_provincias(form) {
	var departamento = eval("document." + form + ".departamento.value");
	var url = "includes/ws/provincias.php";
	var pars = "a1=" + departamento;
	var myAjax = new Ajax.Request( url, {method: "get", parameters: pars,
		onComplete: function(respuesta) {
			var provincias = eval(respuesta.responseText);
			var obj = eval("document." + form + ".provincia");
			obj.length = 0;
			obj.options[0] = new Option("Seleccione...", "")
			for (contador = 0; contador < provincias.length; contador++) {
				obj.options[contador + 1] = new Option(provincias[contador][1], provincias[contador][0]);
			}
			//obj.value = prov;
			buscar_distrito(form);
		}
	} );
}

function buscar_distrito(form) {	
	var departamento = eval("document." + form + ".departamento.value");
	var provincia = eval("document." + form + ".provincia.value");
	var url = "includes/ws/distritos.php";
	var pars = "a1=" + departamento + "&a2=" + provincia;
	var myAjax = new Ajax.Request( url, {method: "get", parameters: pars,
		onComplete: function(respuesta) {
			var distritos = eval(respuesta.responseText);
			var obj2 = eval("document." + form + ".distrito");
			obj2.length = 0;
			obj2.options[0] = new Option("Seleccione...", "");
			for (contador = 0; contador < distritos.length; contador++) {
				obj2.options[contador + 1] = new Option(distritos[contador][1], distritos[contador][0]);
			}
			//obj2.value = dist;
			buscar_cuadrante(form);
		}
	} );
}

function buscar_cuadrante(form) {
	var distrito = eval("document." + form + ".distrito.value");
	var url = "includes/ws/zonas.php";
	var pars = "a1=" + distrito;
	var myAjax = new Ajax.Request( url, {method: "get", parameters: pars,
		onComplete: function(respuesta) {
			var obj3 = eval("document." + form + ".cuadrante");
			obj3.length = 0;
			obj3.options[0] = new Option("Seleccione...", "");
			var cuadrantes = eval(respuesta.responseText);
			for (contador = 0; contador < cuadrantes.length; contador++) {
				obj3.options[contador + 1] = new Option(cuadrantes[contador][1], cuadrantes[contador][0]);
			}
			//obj3.value = cua;
		}
	} );
	if (form == "frmDatos2") {
		if (distrito != "") {
			document.frmDatos2.busqueda_cuadrante.disabled = false; }
		else {
			document.frmDatos2.busqueda_cuadrante.disabled = true; }
	}
}

function cuadrantes() {
	ventana = window.open("buscar_cuadrante.php?direccion=" + document.frmDatos2.direccion.value + "&distrito=" + document.frmDatos2.distrito.value, "ventana_busqueda", "height=500,width=400,status=yes,toolbar=no,scrollbars=yes,menubar=no,location=no");
	ventana.focus();
}

function ver_priorizacion(tipo) {
	if (document.frmDatos2.priorizacion.value == "1") {
		if (confirm("Ya existe una dirección primaria. ¿Desea cambiarla?")) {
			if (tipo == "C") {
				ver_tipo(); }
		}
		else {
			document.frmDatos2.priorizacion.value = "";
		}
	}
}