<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>Sistema de cobranza prejudicial</title><noscript><meta http-equiv="refresh" content="0; URL=login.php"></noscript><script type="text/javascript" src="includes/functions.js"></script><!-- file css vacio, solo para evitar bugs--><link rel="stylesheet" type="text/css" href="../styles/blue/menu.css" /><!-- zpForm para validar los formularios -->
	<!-- Javascript utilities file for the form -->
	<script src="includes/zpform/utils/utils.js" type="text/javascript"></script>
	<!-- Javascript transport file for communicating with the server -->
	<script src="includes/zpform/utils/transport.js" type="text/javascript"></script>
	<!-- basic Javascript file for the form -->
	<script src="includes/zpform/src/form.js" type="text/javascript"></script>
	<!-- CSS file for basic style in the form-->
	<link href="includes/zpform/themes/alternate.css" rel="stylesheet" />
	<!-- Spry para mostrar en pestañas los datos de cliente --><script src="includes/spry/SpryTabbedPanels.js" type="text/javascript"></script>
	<link href="includes/spry/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
	<!-- Calendar para seleccionar fecha en formularios -->
	<style type="text/css">@import url("includes/calendar/calendar-blue2.css");</style>
	<script type="text/javascript" src="includes/calendar/calendar.js"></script>
	<script type="text/javascript" src="includes/calendar/lang/calendar-es.js"></script>
	<script type="text/javascript" src="includes/calendar/calendar-setup.js">
	</script>
	<!-- Prototype para extender funciones de JS -->
	<script src="includes/prototype.js"></script>
<link href="style/blue/blue.css" rel="stylesheet" type="text/css" />
<link href="style/blue/print.css" rel="stylesheet" type="text/css" media="print" />
</head>
<body  topmargin="0" rightmargin="0" bottommargin="0" leftmargin="0"><div id="ykMain"><div id="ykHead"><div id="bgHead"><div id="logo_sys"></div><!-- Negociador --><ul class="derecha"><li><a href="#" title="Products" class="loginArrow">Praeli Papas Josnifer Kevin</a></li><li><a href="#" title="Products" class="loginArrow">ENERO 2011</a></li><li><a href="#" title="Products" class="loginArrow">Seg: 0.000063</a></li></ul><ul class="NewNav"><li><a href="negociacion.php?pag=1&orden=6&ad=d&proveedor=&numdoc=&paterno=&materno=&ubicabilidad=&ciclo=&rgestion=&grupo=&todo=&hist=&tab=" title="Negociación">Negociación</a></li><li><a href="control.php?p=58" title="Control">Control</a></li><li><a href="proyeccion.php?p=58" title="Proyección">Proyección</a></li><li><a href="http://192.168.50.16/crm/crmlogin.html" target="_blank">Predictivo</a></li><li><a class="loginArrow" href="logout.php" title="eProject Login"><strong>Logout</strong></a></li><li></li></ul><!-- Fin Negociador --></div></div><!-- /ykHead --><div id="ykBody"><div id="main_sys">
<link href="style/tables.css" type="text/css" rel="stylesheet" />
<link href="style/filas.css" type="text/css" rel="stylesheet" />
<script src="includes/filas.js"></script>
<script> 
	function activarcompromiso()
	{
		var linea = new String(document.frmDatos.resultado.value);
		var lista = linea.substr(0, 4);
		var tipo = linea.substr(linea.length - 1);
		var r = linea.substr(lista.length + 1, linea.length - (lista.length + 3));
		if (lista == "1000" || lista == "1005" || lista == "1006" || lista == "1021" || lista == "1022" || lista == "1023")
		//if (lista == "1005" || lista == "1006" || lista == "1021" || lista == "1023")
		{
			var targetElement = document.getElementById("resultados");
			targetElement.style.visibility = "visible";
			targetElement.style.position = "relative";
			document.frmDatos.fechacompromiso.value = "";
			document.frmDatos.importecompromiso.value = "0.00";
		}
		else
		{
			var targetElement = document.getElementById("resultados");
			targetElement.style.visibility = "hidden";
			targetElement.style.position = "absolute";
			if (document.frmDatos.fechacompromiso.value == "")
			{
				document.frmDatos.fechacompromiso.value = "01/01/2000";
			}
			if (document.frmDatos.importecompromiso.value == "")
			{
				document.frmDatos.importecompromiso.value = 0;
			}
		}
		if (tipo == "N")
		{
			document.frmDatos.justificacion_a.length = 0;
			var targetElement = document.getElementById("div_justificacion");
			targetElement.style.visibility = "visible";
			targetElement.style.position = "relative";
			var url = "includes/ws/justificaciones_alayza.php";
			var pars = "r=" + r;
			var myAjax = new Ajax.Request( url, {method: "get", parameters: pars, onComplete: function(originalRequest)
			{
					var justificaciones = eval(originalRequest.responseText);
					var obj = document.frmDatos.justificacion_a;
					obj.length = 0;
					for (contador = 0; contador < justificaciones.length; contador++)
					{
						obj.options[contador] = new Option(justificaciones[contador][1], justificaciones[contador][0]);
					}
				}
			} );
		}
		else
		{
			var obj = document.frmDatos.justificacion_a;
			obj.length = 0;
			obj.options[0] = new Option("", 0);
			var targetElement = document.getElementById("div_justificacion");
			targetElement.style.visibility = "hidden";
			targetElement.style.position = "absolute";
		}
	}
 
	function eliminar()
	{
		if (confirm("¿Está seguro de eliminar esta gestión?"))
		{
			document.frmDatos.acc.value = "0";
			document.frmDatos.submit();
		}
	}
 
	function ver_priorizacion(tipo)
	{
		if (tipo == "direccion")
		{
			var obj = document.frmDatos2;
			var mensaje = "esta dirección como primaria";
		}
		else
		{
			var obj = document.frmDatos3;
			var mensaje = "este teléfono como primario";
		}
		if (obj.priorizacion.value == "1")
		{
			if (!confirm("¿Está seguro de establecer " + mensaje + "?"))
			{
				obj.priorizacion.value = "2";
			}
		}
	}
 
	var f;
	var prov = "", dist = "", cua = "";
	function buscar_provincias(form)
	{
		f = form;
		var url = "includes/ws/provincias.php";
		var pars = "a1=" + eval("document." + form + ".departamento.value");
		var myAjax = new Ajax.Request( url, {method: "get", parameters: pars, onComplete: function(originalRequest)
			{
				var provincias = eval(originalRequest.responseText);
				var obj = eval("document." + f + ".provincia");
				obj.length = 0;
				obj.options[0] = new Option("Seleccione...", "");
				for (contador = 0; contador < provincias.length; contador++)
				{
					obj.options[contador + 1] = new Option(provincias[contador][1], provincias[contador][0]);
				}
				obj.value = prov;
				buscar_distrito(f);
			}
		} );
	}
 
	function buscar_distrito(form)
	{
		f = form;
		var departamento = eval("document." + form + ".departamento.value");
		var provincia = eval("document." + form + ".provincia.value");
		var url = "includes/ws/distritos.php";
		var pars = "a1=" + departamento + "&a2=" + provincia;
		var myAjax = new Ajax.Request( url, {method: "get", parameters: pars, onComplete: mostrar_distrito} );
	}
 
	function mostrar_distrito(originalRequest)
	{
		var distritos = eval(originalRequest.responseText);
		var obj2 = eval("document." + f + ".distrito");
		obj2.length = 0;
		obj2.options[0] = new Option("Seleccione...", "");
		for (contador = 0; contador < distritos.length; contador++)
		{
			obj2.options[contador + 1] = new Option(distritos[contador][1], distritos[contador][0]);
		}
		obj2.value = dist;
		buscar_cuadrante(f);
	}
 
	function buscar_cuadrante(form)
	{
		f = form;
		var distrito = eval("document." + form + ".distrito.value");
		var url = "includes/ws/zonas.php";
		var pars = "a1=" + distrito;
		var myAjax = new Ajax.Request( url, {method: "get", parameters: pars, onComplete: mostrando_cuadrante} );
		if (form == "frmDatos2")
		{
			if (distrito != "")
			{
				document.frmDatos2.busqueda_cuadrante.disabled = false;
			}
			else
			{
				document.frmDatos2.busqueda_cuadrante.disabled = true;
			}
		}
	}
 
	function mostrando_cuadrante(originalRequest) {
		var obj3 = eval("document." + f + ".cuadrante");
		obj3.length = 0;
		obj3.options[0] = new Option("Seleccione...", "");
		var cuadrantes = eval(originalRequest.responseText);
		for (contador = 0; contador < cuadrantes.length; contador++) {
			obj3.options[contador + 1] = new Option(cuadrantes[contador][1], cuadrantes[contador][0]);
		}
		obj3.value = cua;
	}
 
	
	function eliminar_tarea() {
		if (confirm("¿Está seguro de eliminar la tarea?")) {
			document.frmDatos4.acc1.value = "etar";
			document.frmDatos4.submit();	}	}
	
 
	function estado(control, tipo, id) {
		var vtipo; var vestado;
		if (tipo == "d") {
			vtipo = "de la dirección"; }
		else {
			vtipo = "del teléfono"; }
		if (control.checked) {
			vestado = "inactivo a activo"; }
		else {
			vestado = "activo a inactivo"; }
		if (confirm("¿Está seguro de cambiar el estado " + vtipo + " de " + vestado + "?")) {
			var url = "includes/ws/estado.php";
			var estado;
			if (control.checked) {
				estado = "1"; }
			else {
				estado = "0"; }
			var pars = "tipo=" + tipo + "&id=" + id + "&estado=" + estado;
 
			var myAjax = new Ajax.Request( url, {method: "get", parameters: pars} ); }
		else {
			control.checked = !control.checked; }
		var obj = eval("document.frmDireccion." + tipo + id);
		obj.disabled = !control.checked;
	}
 
	function priorizacion(tipo, cli, id) {
		var obj = eval("document.frmDireccion.e" + tipo.substr(1, 1) + id);
		var cancelar = 0;
		var vtipo;
		if (tipo == "pd") {
			vtipo = "esta dirección"; }
		else {
			vtipo = "este teléfono"; }
		if (obj.checked) {
			if (confirm("¿Está seguro de establecer la priorización de " + vtipo + " como primaria?")) {
				var obje = document.frmDireccion;
				for (contador = 0; contador < obje.length; contador++) {
					if (obje[contador].type == "checkbox" && obje[contador].id.substr(0, 1) == "e") {
						obje[contador].disabled = false; }	}
				var url = "includes/ws/estado.php";
				var estado;
				var pars = "tipo=" + tipo + "&cli=" + cli + "&id=" + id;
				var myAjax = new Ajax.Request( url, {method: "get", parameters: pars} );
				obj.disabled = true;	}
			else {
				cancelar = 1;
				obj.disabled = false;	}	}
		else {
			alert("El estado de " + vtipo + "es inactivo.");
			cancelar = 1;	}
		if (cancelar == 1) {
			var url = "includes/ws/priorizacion.php";
			var pars = "tipo=" + tipo + "&cli=" + cli;
			var myAjax = new Ajax.Request( url, {method: "get", parameters: pars, onComplete: mostrar_priorizacion} );	}	}
 
	function  mostrar_priorizacion(originalRequest) {
		if (originalRequest.responseText != "d" && originalRequest.responseText != "t")
		{
			var control = originalRequest.responseText;
			eval("document.frmDireccion." + control + ".checked = true;");
			eval("document.frmDireccion.e" + control + ".disabled = true;");
		}
		else
		{
			var tipo = originalRequest.responseText;
			var objd = document.frmDireccion;
			for (i = 0; i < objd.length; i++)
			{
				if (objd[i].type == "radio" && objd[i].id.substr(0, 1) == tipo)
				{
					objd[i].checked = false;
				}
			}
		}
	}
 
	function cambiar_direccion() {
		var obj = document.frmDireccion;
		if (obj.direcciones.value == "") {
			alert("Seleccione la dirección");
			obj.direcciones.focus();
			return false;	}
		cuenta = 0;
		for (contador = 0; contador < obj.elements.length; contador++) {
			if (obj.elements[contador].type == "checkbox" && obj.elements[contador].id == "telefono" && obj.elements[contador].checked) {
			cuenta++; }	}
		if (cuenta == 0) {
			alert("Debe seleccionar al menos un teléfono");
			return false; }
		obj.acc.value = "cambiar";
		obj.submit(); }
	
	function cuadrantes() {
		ventana = window.open("buscar_cuadrante.php?direccion=" + document.frmDatos2.direccion.value + "&distrito=" + document.frmDatos2.distrito.value, "ventana_busqueda", "height=500,width=400,status=yes,toolbar=no,scrollbars=yes,menubar=no,location=no");
		ventana.focus(); }
 
	function mostrar_ocultar(ocultar) {
		var estilos = new Array();
		var columna = 8;
		if (document.styleSheets[columna].cssRules)
			estilos = document.styleSheets[columna].cssRules
		else if (document.styleSheets[columna].rules)
			estilos = document.styleSheets[columna].rules
		else return;
		if (ocultar && estilos[estilos.length - 1].style.display == "none") {
			return }
		if (estilos[estilos.length - 1].style.display == "none") {
			estilos[estilos.length - 1].style.display = "";
			estilos[estilos.length - 2].style.display = "";
			$("mas_menos").innerHTML = "(-)";
		}
		else {
			estilos[estilos.length - 1].style.display = "none";
			estilos[estilos.length - 2].style.display = "none";
			$("mas_menos").innerHTML = "(+)";
		}
	}
 
	function imprimir() {
		//mostrar_ocultar(true);
		print(); }
 
	function modificar_ubigeo() {
		if (document.frmDireccion.direcciones.value == "NULL") {
			alert("Seleccione la dirección");
			document.frmDireccion.direcciones.focus();
			return false; }
		if (document.frmDireccion.departamento.value == "") {
			alert("Seleccione el departamento");
			document.frmDireccion.departamento.focus();
			return false; }
		if (document.frmDireccion.provincia.value == "") {
			alert("Seleccione la provincia");
			document.frmDireccion.provincia.focus();
			return false; }
		if (document.frmDireccion.distrito.value == "") {
			alert("Seleccione el distrito");
			document.frmDireccion.distrito.focus();
			return false; }
		if (document.frmDireccion.cuadrante.value == "") {
			alert("Seleccione el cuadrante");
			document.frmDireccion.cuadrante.focus();
			return false; }
		if (confirm("¿Está seguro de modificar el ubigeo y cuadrante de la dirección seleccionada?")) {
			document.frmDireccion.acc.value = "cambiar_cuadrante";
			document.frmDireccion.submit();
		}
	}
 
	function ubigeo_direccion(clidid) {
		var url = "includes/ws/ubigeo_direccion.php";
		var pars = "id=" + clidid;
		var myAjax = new Ajax.Request( url, {method: "get", parameters: pars, onComplete: mostrar_ubigeo_direccion} );
	}
 
	function mostrar_ubigeo_direccion(originalRequest) {
		var ubigeo = eval(originalRequest.responseText);
		var obj = document.frmDireccion;
		obj.departamento.value = ubigeo[0];
		prov = ubigeo[1];
		dist = ubigeo[2];
		cua = ubigeo[3];
		buscar_provincias("frmDireccion");
	}
 
	function cambio_indicador() {
		var obj = document.frmDatos.telefono_gestion;
		var items = document.frmDatos.telefono_gestion.length;
		if (document.frmDatos.indicador.value == "1") { /* OC */
			if (obj.options[items - 1].value == "NULL") {
				obj.length = items - 1; }
		}
 
		if (document.frmDatos.indicador.value != "1") { /* IC */
			if (items == 0 || obj.options[items - 1].value != "NULL") {
				obj.options[items] = new Option("Otro", "NULL"); }
		}
 
		var targetElement = document.getElementById("div_contacto");
		var objtc = document.frmDatos.tipo_contacto;
		var itemstc = document.frmDatos.tipo_contacto.length;
		if (document.frmDatos.indicador.value == "4") { /* IR */
			document.frmDatos.telefono_gestion.value = "NULL";
			if (itemstc == 0 || objtc.options[itemstc - 1].value != "NULL") {
				objtc.options[itemstc - 1] = new Option("", "NULL");
			}
			document.frmDatos.tipo_contacto.value = "NULL";
			targetElement.style.visibility = "hidden";
			targetElement.style.position = "absolute";
		}
		else {
			if (objtc.options[itemstc - 1].value == "NULL") {
				objtc.length = itemstc - 1; }
			targetElement.style.visibility = "visible";
			targetElement.style.position = "relative";
		}
	}
 
	arr_telefonos = new Array('993574781', '980412532', '4525979');
	function buscar_telefono()
	{
		var vnumero = document.frmDatos3.numerotelefono.value;
		var vnuevonumero = "";
		for (contador = 0; contador < vnumero.length; contador++)
		{
			if (parseInt(vnumero.charAt(contador)) || vnumero.charAt(contador) == "0")
			{
				vnuevonumero = vnuevonumero + vnumero.charAt(contador);
			}
		}
		document.frmDatos3.numerotelefono.value = vnuevonumero;
		$("span_mensaje_telefono").style.visibility = "hidden";
		for (contador = 0; contador < arr_telefonos.length; contador++)
		{
			if (arr_telefonos[contador] == document.frmDatos3.numerotelefono.value)
			{
				$("span_mensaje_telefono").style.visibility = "visible";
				$("span_mensaje_telefono").innerHTML = "<b>El número " + document.frmDatos3.numerotelefono.value + " ya fue registrado</b>";
				document.frmDatos3.numerotelefono.value = "";
				document.frmDatos3.numerotelefono.focus();
				contador = arr_telefonos.length;
			}
		}
	}
 
	function validar_fecha() {
		fecha = document.frmDatos.fechagestion.value;
		var fecha = fecha.split("/");
		if (new Date(2011, 1, 5) < new Date(fecha[2], fecha[1], fecha[0])) {
			hoy = "2011, 1, 5";
			hoy = hoy.split(", ");
			alert("No se permiten fechas posteriores a " + hoy[2] + "/" + hoy[1] + "/" + hoy[0]);
			document.frmDatos.fechagestion.value = ""; }
	}
 
	function fecha_valida(date, y, m, d)
	{
		if (new Date() < date)
		{
			return true;
		}
		return false;
	}
 
	function activar()
	{
		var codigo = document.frmDatos5.tipodoc.value;
		switch (codigo) {
			case "1":
				document.frmDatos5.dni.size = 8;
				break;
			case "2":
				document.frmDatos5.dni.size = 11;
				break;
			default: // 3, 4, 5, 6
				document.frmDatos5.dni.size = 12;
		}
	}
 
	function activar2()
	{
		var codigo = document.frmDatos5.tipodoc.value;
		switch (codigo)
		{
			case "1":
				document.frmDatos5.dni.value = document.frmDatos5.dni.value.substring(0, 8);
				break;
			case "2":
				document.frmDatos5.dni.value = document.frmDatos5.dni.value.substring(0, 11);
				break;
			default: // 3, 4, 5, 6
				document.frmDatos5.dni.value = document.frmDatos5.dni.value.substring(0, 12);
		}
	}
 
	function grabar_ubicabilidad(horario)
	{
		$("span_ubicabilidad").style.visibility = "visible";
		var url = "includes/ws/grabar_ubicabilidad.php";
		var pars = "id=438602&u=" + horario;
		var myAjax = new Ajax.Request( url, {method: "get", parameters: pars, onComplete: function(originalRequest)
			{
				$("span_ubicabilidad").style.visibility = "hidden";
			}
		} );
	}
 
	function mostrar_gestiones(cli, pag, capa)
	{
		var url = "gestiones_negociacion.php";
		var pars = "cli=" + cli + "&r_id=" + r_id + "&tipo=" + capa + "&pag=" + pag;
		var myAjax = new Ajax.Request( url, {method: "get", parameters: pars, onComplete: function(originalRequest)
			{
				$("span_gestiones_" + capa).innerHTML = originalRequest.responseText;
			}
		} );
	}
 
	function cambiar_cuenta()
	{
		var nuevo = document.frmDatos.cuenta2.value;
		var qs = location.search.substr(1);
		qs = qs.split("&");
		qs1 = "";
		for (i = 0; i < qs.length; i++)
		{
			tmp = qs[i].split("=");
			if (tmp[0] == "id")
			{
				qs[i] = tmp[0] + "=" + nuevo;
			}
			if (tmp[0] == "tab")
			{
				qs[i] = tmp[0] + "=1";
			} // Tab Gestion
			qs1 = qs1 + qs[i];
			if (i + 1 != qs.length)
			{
				qs1 = qs1 + "&";
			}
		}
		self.location.href = "negociacion.php?" + qs1;
	}
 
	function ver_gestiones(capa, capa1)
	{
		$("span_gestiones_" + capa).style.visibility = "hidden";
		$("span_gestiones_" + capa).style.position = "absolute";
		$("span_gestiones_" + capa1).style.visibility = "visible";
		$("span_gestiones_" + capa1).style.position = "relative";
	}
 
	function nueva_tarea()
	{
		if (document.frmDatos.nuevatarea.checked)
		{
			$("div_tarea").style.visibility = "visible";
			$("div_tarea").style.position = "relative";
			if (document.frmDatos.fechatarea.value == "31/12/1969")
			{
				document.frmDatos.fechatarea.value = "";
			}
			if (document.frmDatos.horatarea.value == "00:00")
			{
				document.frmDatos.horatarea.value = "";
			}
		}
		else
		{
			$("div_tarea").style.visibility = "hidden";
			$("div_tarea").style.position = "absolute";
			if (document.frmDatos.fechatarea.value == "")
			{
				document.frmDatos.fechatarea.value = "31/12/1969";
			}
			if (document.frmDatos.horatarea.value == "")
			{
				document.frmDatos.horatarea.value = "00:00";
			}
		}
	}
 
	function cambio_fecha_compromiso()
	{
		var linea = new String(document.frmDatos.resultado.value);
		var lista = linea.substr(0, 4);
		if (lista == "1000")
		{
			fecha = document.frmDatos.fechacompromiso.value;
			var fecha = fecha.split("/");
			if (new Date(2011, 1, 5) > new Date(fecha[2], fecha[1], fecha[0])) 
			{
				hoy = "2011, 1, 5";
				hoy = hoy.split(", ");
				alert("No se permiten fechas anteriores a " + hoy[2] + "/" + hoy[1] + "/" + hoy[0]);
				document.frmDatos.fechacompromiso.value = ""; 
			}
		}
		else
		{
			document.frmDatos.fechatarea.value = document.frmDatos.fechacompromiso.value;
		}
	}
	
	function validar_importe_compromiso()
	{
		var linea = new String(document.frmDatos.resultado.value);
		var lista = linea.substr(0, 4);
		if (lista == "1000" || lista == "1005" || lista == "1006" || lista == "1021"  || lista == "1022" || lista == "1023")
		//if (lista == "1005" || lista == "1006" || lista == "1021" || lista == "1023")
		{
			var importe = document.frmDatos.importecompromiso.value;	
			if (importe <= 0 ) 
			{
				alert("No se permite importe 0 (cero)");
				document.frmDatos.importecompromiso.value = 0; 
				document.frmDatos.importecompromiso.focus(); 
			}
		}
		else
		{
			//document.frmDatos.fechatarea.value = document.frmDatos.fechacompromiso.value;
		}
	}
 
	function validar_tipo_contacto()
	{
		var linea = new String(document.frmDatos.resultado.value);
		var lista = linea.substr(0, 4);
		if (lista == "1000" || lista == "1005" || lista == "1006" || lista == "1021" || lista == "1022" || lista == "1023")
		{
			var tipo = document.frmDatos.tipo_contacto.value;	
			//if (tipo >= 3) 			
			if (tipo != 1 && tipo != 2 && tipo != 6 && tipo != 8 ) 
			{
				alert("No se permite este tipo de contacto");
				document.frmDatos.tipo_contacto.value = 1; 
			}
		}
		else
		{
			//document.frmDatos.fechatarea.value = document.frmDatos.fechacompromiso.value;
		}
	}
</script>
<style type="text/css"> 
	.oculto
	{
		display:none;
	}
	.numero_oculto
	{
		display:none;
		text-align:right;
	}
</style>
<div id="TabbedPanels1" class="TabbedPanels"><ul class="TabbedPanelsTabGroup"><li class="TabbedPanelsTab" tabindex="0">Clientes</li><li class="TabbedPanelsTab" tabindex="1">Gestión</li><li class="TabbedPanelsTab" tabindex="3">Tareas</li><li class="TabbedPanelsTab" tabindex="4">Direcciones</li><li class="TabbedPanelsTab" tabindex="5">Teléfonos</li><li class="TabbedPanelsTab" tabindex="6">Contactos</li></ul><div class="TabbedPanelsContentGroup"><div class="TabbedPanelsContent"> <!-- Clientes --><div id="areaList"><a href="javascript:imprimir()"><span class="impresion"></span></a><!-- D A T O S  D E L  C L I E N T E --><table width="100%" id="design4"><caption>Datos del cliente</caption><tr class="noborde"><td valign="top"><table width="100%" id="design3"><tr><td width="50%" class="cellhead">Apellido Paterno:</td><td width="50%">Lobaton</td></tr><tr><td class="cellhead">Apellido Materno:</td><td>Fernandez</td></tr><tr><td class="cellhead">Nombre:</td><td>Pedro Alejandro</td></tr><tr><td class="cellhead">Tipo de Cliente:</td><td>Natural</td></tr></table></td><td valign="top"><table width="100%" id="design3"><tr><td class="cellhead">Clasificación:</td><td>Ubicables</td></tr><tr><td class="cellhead">Zona:</td><td>SIN ZONA</td></tr><tr><td class="cellhead">Número de Documento:</td><td>D.N.I. : 25550477</td></tr><tr><td class="cellhead">Observaciones:</td><td></td></tr></table></td></tr><tr class="noborde"><td class="cellhead" colspan="2"><form name="frmUbicabilidad"><table width="100%" id="design3"><tr><td class="cellhead">Horario de ubicabilidad:</td><td><input type="radio" name="ubicabilidad" onchange="grabar_ubicabilidad(1);" /></td><td>(M / M – 07:00 hrs -> 10:00 hrs)</td><td><input type="radio" name="ubicabilidad" onchange="grabar_ubicabilidad(2);" /></td><td>(M / T – 10:01 hrs -> 14:00 hrs)</td><td><input type="radio" name="ubicabilidad" onchange="grabar_ubicabilidad(3);" /></td><td>(T / T – 14:01 hrs -> 18:00 hrs)</td><td><input type="radio" name="ubicabilidad" onchange="grabar_ubicabilidad(4);" /></td><td>(T / N – 18:01 hrs -> 21:00 hrs)</td><td><span id="span_ubicabilidad" style="color:#FF0000; font-weight:bold; visibility:hidden;">Grabando...</span></td></tr></table></form></td></tr></table></div><!-- D I R E C C I O N E S --><div id="areaList"><table width="100%" id="design4"><form name="frmDireccion" method="post"><caption>Direcciones y Teléfonos</caption><thead><tr><th>Dirección</th><th>Origen</th><th>Ubigeo</th><th>Cuadrante</th><th>Número</th><th>Observaciones</th><th>Estado</th><th colspan="2">Priorización<br />&nbsp;<sub>Dir.</sub> &nbsp;&nbsp; | &nbsp;&nbsp; <sub>Tel.</sub></th></tr></thead><tbody><tr><td>Calle Jaspampa 209</td><td>Casa</td><td>CALLAO</td><td align="center">404-C03</td><td>&nbsp;</td><td></td><td align="center"><input type="checkbox" id="ed307568" onclick="estado(this, 'd', '307568');" disabled /></td><td align="center"><input type="radio" name="dprioridad" id="d307568" onchange="priorizacion('pd', '438602', '307568');" disabled /></td><td>&nbsp;</td></tr><tr class="rows2"><td align="right"><input type="checkbox" name="telefono[]" id="telefono" value="908865" />&nbsp;</td><td class="bgdir">Casa</td><td class="bgdir" colspan="2">&nbsp;</td><td class="bgdir">980412532</td><td class="bgdir"></td><td class="bgdir" align="center"><input type="checkbox" id="et908865" onclick="estado(this, 't', '908865');" checked /></td><td class="bgdir">&nbsp;</td><td class="bgdir" align="center"><input type="radio" name="tprioridad" id="t908865" onchange="priorizacion('pt', '438602', '908865');" /></td></tr><tr class="rows2"><td align="right"><input type="checkbox" name="telefono[]" id="telefono" value="380552" />&nbsp;</td><td class="bgdir">Casa</td><td class="bgdir" colspan="2">&nbsp;</td><td class="bgdir">4525979</td><td class="bgdir"></td><td class="bgdir" align="center"><input type="checkbox" id="et380552" onclick="estado(this, 't', '380552');" checked /></td><td class="bgdir">&nbsp;</td><td class="bgdir" align="center"><input type="radio" name="tprioridad" id="t380552" onchange="priorizacion('pt', '438602', '380552');" /></td></tr><tbody><tr><td>Calle Jaspampa 209 Tarapaca Callao</td><td>Casa</td><td>CALLAO</td><td align="center">-</td><td>&nbsp;</td><td></td><td align="center"><input type="checkbox" id="ed944816" onclick="estado(this, 'd', '944816');" checked disabled /></td><td align="center"><input type="radio" name="dprioridad" id="d944816" onchange="priorizacion('pd', '438602', '944816');" checked /></td><td>&nbsp;</td></tr><tbody><tr><td>Calle Jaspampa 209Tarapaca Callao</td><td>Oficina</td><td>CALLAO</td><td align="center">-</td><td>&nbsp;</td><td>Pensionista</td><td align="center"><input type="checkbox" id="ed944815" onclick="estado(this, 'd', '944815');" disabled /></td><td align="center"><input type="radio" name="dprioridad" id="d944815" onchange="priorizacion('pd', '438602', '944815');" disabled /></td><td>&nbsp;</td></tr><tr class="rows2"><td align="right"><input type="checkbox" name="telefono[]" id="telefono" value="1016356" />&nbsp;</td><td class="bgdir">Casa</td><td class="bgdir" colspan="2">&nbsp;</td><td class="bgdir">993574781</td><td class="bgdir"></td><td class="bgdir" align="center"><input type="checkbox" id="et1016356" onclick="estado(this, 't', '1016356');" /></td><td class="bgdir">&nbsp;</td><td class="bgdir" align="center"><input type="radio" name="tprioridad" id="t1016356" onchange="priorizacion('pt', '438602', '1016356');" disabled /></td></tr><tr class="no-print"><td align="right"><select name="direcciones" onchange="ubigeo_direccion(this.value);"><option value="307568">Calle Jaspampa 209</option><option value="944816">Calle Jaspampa 209 Tarapaca Callao</option><option value="944815">Calle Jaspampa 209Tarapaca Callao</option><option value="NULL" selected>Sin dirección</option></select></td><td colspan="8"><input type="button" value="Cambiar dirección" class="btn" onclick="cambiar_direccion();" /><input type="hidden" name="acc" value="" /></td></tr><tr class="no-print"><td colspan="9">Departamento:<select name="departamento" onchange="buscar_provincias('frmDireccion');"><option value="">Seleccione...</option><option label="AMAZONAS" value="01">AMAZONAS</option>
<option label="ANCASH" value="02">ANCASH</option>
<option label="APURIMAC" value="03">APURIMAC</option>
<option label="AREQUIPA" value="04">AREQUIPA</option>
<option label="AYACUCHO" value="05">AYACUCHO</option>
<option label="CAJAMARCA" value="06">CAJAMARCA</option>
<option label="CALLAO" value="07">CALLAO</option>
<option label="CUSCO" value="08">CUSCO</option>
<option label="HUANCAVELICA" value="09">HUANCAVELICA</option>
<option label="HUANUCO" value="10">HUANUCO</option>
<option label="ICA" value="11">ICA</option>
<option label="JUNIN" value="12">JUNIN</option>
<option label="LA LIBERTAD" value="13">LA LIBERTAD</option>
<option label="LAMBAYEQUE" value="14">LAMBAYEQUE</option>
<option label="LIMA" value="15">LIMA</option>
<option label="LORETO" value="16">LORETO</option>
<option label="MADRE DE DIOS" value="17">MADRE DE DIOS</option>
<option label="MOQUEGUA" value="18">MOQUEGUA</option>
<option label="PASCO" value="19">PASCO</option>
<option label="PIURA" value="20">PIURA</option>
<option label="PUNO" value="21">PUNO</option>
<option label="SAN MARTIN" value="22">SAN MARTIN</option>
<option label="TACNA" value="23">TACNA</option>
<option label="TUMBES" value="24">TUMBES</option>
<option label="UCAYALI" value="25">UCAYALI</option>
</select>&nbsp;&nbsp;Provincia:<select name="provincia" onchange="buscar_distrito('frmDireccion');"><option value="">Seleccione...</option></select>&nbsp;&nbsp;Distrito:<select name="distrito" onchange="buscar_cuadrante('frmDireccion');"><option value="">Seleccione...</option></select>&nbsp;&nbsp;Cuadrante:<select name="cuadrante"><option value="">Seleccione...</option></select>&nbsp;&nbsp;<input type="hidden" name="codigo_form" value="20110105134713" /><input type="button" value="Modificar ubigeo" class="btn" onclick="modificar_ubigeo();" /></td></tr></tbody></form></table></div><br /><!-- C O N T A C T O S --><div id="areaList"><div style="overflow:auto"><table width="100%" id="tabla_cuentas" class="tabla_listado"><caption>Cuentas <a href="#" onclick="mostrar_ocultar(false);"><span id="mas_menos">(+)</span></a></caption><thead><tr><!-- nohighlight --><th>Cta1</th><th>Cta2</th><th>Cta3</th><th>Moneda</th><th>Capital total</th><th>Capital vencido</th><th class="oculto">Intereses</th><th class="oculto">Gastos</th><th class="oculto">Honorarios</th><th class="oculto">Penalidad</th><th>Total</th><th>Total vencido</th><th>Proveedor - Producto</th><th>Mora</th><th class="oculto">Cuotas crédito</th><th class="oculto">Cuotas pagadas</th><th class="oculto">Cuotas vencidas</th><th>Valor cuota</th><th>F. Vcto</th><th class="oculto">F. Ingreso</th><th class="oculto">Cartera</th><th>Grupo</th><th>Ciclo</th><th class="oculto">Auxiliar1</th><th class="oculto">Auxiliar2</th><th></th></tr></thead><tbody><tr id="c25550477__4487002000035378"><td>25550477</td><td>&nbsp;</td><td>4487002000035378</td><td>Dolares</td><td class="numeros">304.00</td><td class="numeros">0.00</td><td class="numero_oculto">0.00</td><td class="numero_oculto">0.00</td><td class="numero_oculto">0.00</td><td class="numero_oculto">0.00</td><td class="numeros">879.00</td><td class="numeros">0.00</td><td>Citibank - Visa Silver</td><td class="numeros">659</td><td class="numero_oculto">15</td><td class="numero_oculto">0</td><td class="numero_oculto">0</td><td class="numeros">122.00</td><td align="center">24/11/2008</td><td align="center" class="oculto">09/09/2010</td><td class="oculto">Castigada</td><td>VENTA</td><td class="numeros"></td><td class="oculto"></td><td class="oculto"></td><td></td></tr> <!-- cuentas --></tbody></table></div><table width="100%" id="tabla_cuentas_detalle" class="no-print"><caption>Detalle de la cuenta</caption><tr><td><b>No hay detalles de cuenta</b></td></tr></table><!-- GESTIONES --><table width="100%" id="design4"><tr><td><caption>Gestiones&nbsp;&nbsp;&nbsp;<input type="radio" name="gt" onclick="ver_gestiones('campo', 'call');" checked />Call Center&nbsp;&nbsp;&nbsp;<input type="radio" name="gt" onclick="ver_gestiones('call', 'campo');" />Campo</caption></td></tr></table><span id="span_gestiones_call" style="visibility:visible;position:relative;"></span><span id="span_gestiones_campo" style="visibility:hidden;position:absolute;"></span><!-- PAGOS --><table width="100%" id="tabla_pagos"><caption>Pagos</caption><tr><td><b>No hay pagos</b></td></tr></tbody></table></div><br /><div class="no-print"><input class="btn" type="button" value="Siguiente &raquo;" onclick="self.location.href='negociacion.php?opc=ver&pag=1&orden=6&ad=d&id=28407914&id1=432103&registro=1&proveedor=&numdoc=&paterno=&materno=&ubicabilidad=&ciclo=&rgestion=&grupo=&todo=&hist=&tab=';" />&nbsp;&nbsp;&nbsp;<input class="btn" type="button" value="Regresar" onclick="self.location.href='negociacion.php?pag=1&orden=6&ad=d&proveedor=&numdoc=&paterno=&materno=&ubicabilidad=&ciclo=&rgestion=&grupo=&todo=&hist=&tab=';" />&nbsp;&nbsp;&nbsp;<br /><br /></div></div> <!-- Fin Clientes --><div class="TabbedPanelsContent"> <!-- Gestion --><div id="areaForm"><div class="zpFormContent"><table width="100%"><tr><td><!-- Seccion de gestión --><fieldset><legend>Proceso de Gestión</legend><form name="frmDatos" id="userForm" class="zpForm" method="post" style="text-align:left"><label for="cuenta2" class="zpFormLabel">Cuenta:</label><select id="cuenta2" name="cuenta2[]" class="zpFormRequired" multiple size="1"><option value="28413259" selected>25550477 -  - 4487002000035378 - Visa Silver - US$ 0.00</option></select><br /><label for="cliente" class="zpFormLabel">Cliente:</label><input name="clientes" type="text" class="zpFormNotRequired" id="clientes" value="Lobaton Fernandez, Pedro Alejandro" size="40" disabled /><input name="cliente" type="hidden" id="cliente" value="438602"/><br /><label for="fechagestion" class="zpFormLabel">Fecha Gestión:</label><input type="text" class="zpFormRequired zpFormDate" name="fechagestion" id="fechagestion" value="05/01/2011" size="11" maxlength="10" disabled /><br /><label for="resultado" class="zpFormLabel">Resultado Gestión:</label><select name="resultado" class="zpFormRequired" onchange="activarcompromiso();"><option value="">Seleccione...</option><option value="1000-1-P">1000 - Contacto Efectivo - Compromiso de Pago</option><option value="1002-53-P">1002 - Contacto Efectivo - Compromiso Mes Entrante</option><option value="1003-55-P">1003 - Contacto Efectivo - Interesado en Condonación</option><option value="1005-27-P">1005 - Contacto Efectivo - Pago Total</option><option value="1006-28-P">1006 - Contacto Efectivo - Cuenta al Día</option><option value="1007-56-P">1007 - Contacto Efectivo - Interesado en Pago Total</option><option value="1019-29-N">1019 - Contacto Efectivo - Refinanciamiento en Trámite</option><option value="1021-30-P">1021 - Contacto Efectivo - Pago Parcial</option><option value="1022-70-P">1022 - Contacto Efectivo - Compromiso pago Parcial</option><option value="1023-71-N">1023 - Contacto Efectivo - Ya Pagó</option><option value="5002-45-P">5002 - Contacto Efectivo - Convenio de Pago</option><option value="5012-60-P">5012 - Contacto Efectivo - Entrega de Facturas</option><option value="5013-61-P">5013 - Contacto Efectivo - Confirmacion de Entrega</option><option value="5014-62-P">5014 - Contacto Efectivo - Cobranza Preventiva</option><option value="5018-73-P">5018 - Contacto Efectivo - Comunicado - RPP</option><option value="1001-2-N">1001 - Contacto No Efectivo - Mensaje a Terceros</option><option value="1004-3-N">1004 - Contacto No Efectivo - Contacto sin Compromiso</option><option value="1008-65-P">1008 - Contacto No Efectivo - Recordatorio de Pago</option><option value="1009-74-N">1009 - Contacto No Efectivo - Seguimiento</option><option value="1011-64-P">1011 - Contacto No Efectivo - Desacuerdo</option><option value="1014-6-N">1014 - Contacto No Efectivo - Dificultad de Pago</option><option value="1015-7-N">1015 - Contacto No Efectivo - Renuente</option><option value="1029-4-P">1029 - Contacto No Efectivo - Tercero se Responsabiliza</option><option value="3030-68-P">3030 - Contacto No Efectivo - Devolución</option><option value="4002-44-N">4002 - Contacto No Efectivo - Compromiso en trámite</option><option value="5016-67-N">5016 - Contacto No Efectivo - Observacion</option><option value="1010-10-N">1010 - De Baja - Cuenta Errada</option><option value="1017-8-P">1017 - De Baja - Titular Fallecido</option><option value="1030-32-P">1030 - De Baja - Dejar de Gestionar a Solicitud</option><option value="1013-19-N">1013 - Inubicable - Inubicable</option><option value="1018-54-N">1018 - No Contacto - No Contacto</option><option value="1024-72-N">1024 - No Contacto - Ilocalizado Call</option><option value="4001-36-P">4001 - No Contacto - Verificar en campo</option><option value="5008-52-N">5008 - No Contacto - Actualización de Datos</option><option value="5010-57-N">5010 - No Contacto - Busqueda Externa</option><option value="5011-58-N">5011 - No Contacto - Busqueda Avanzada</option><option value="5015-63-P">5015 - Pendiente - Documento en Transito</option><option value="5017-69-P">5017 - Pendiente - Envio de Carta</option></select><br /><div id="div_justificacion" style="visibility:hidden;position:absolute;"><label for="justificacion_a" class="zpFormLabel">Justificaci&oacute;n:</label><select name="justificacion_a" class="zpFormRequired"></select><br /></div><div id="resultados" style="visibility:hidden"><label for="fechacompromiso" class="zpFormLabel">F. Compromiso:</label><input type="text" class="zpFormRequired zpFormDate" name="fechacompromiso" id="fechacompromiso" value="" size="11" maxlength="10" onchange="cambio_fecha_compromiso();" /><input type="button" id="bcalendario1" value=" ... " />
										<script type="text/javascript">
											Calendar.setup(	{
											inputField : "fechacompromiso", // ID of the input field
											ifFormat : "%d/%m/%Y", // the date format
											button : "bcalendario1", // ID of the button   
											weekNumbers : false,
											range : [1900, 2050] } );</script><br /><label for="moneda" class="zpFormLabel">Moneda:</label><input type="text" size="11" class="zpFormNotRequired" name="moneda" value="Dolares" disabled /><br /><label for="importecompromiso" class="zpFormLabel">Imp. Compromiso:</label><input type="text" class="zpFormRequired zpFormFloat" name="importecompromiso" id="importecompromiso" value="0.00" onchange="validar_importe_compromiso();" size="10" maxlength="15" style="text-align:right" /><br /><!--<br /> --></div><label for="indicador" class="zpFormLabel">Indicador:</label><select name="indicador" class="zpFormRequired" onchange="cambio_indicador();"><option label="OC - Llamada de salida" value="1">OC - Llamada de salida</option>
<option label="IC - Llamada entrante" value="2">IC - Llamada entrante</option>
<option label="IR - Información relevante" value="4">IR - Información relevante</option>
</select><br /><div id="div_contacto"><label for="telefono_gestion" class="zpFormLabel">Telef. Gestionado:</label><select name="telefono_gestion" class="zpFormRequired" size="4"><option value="">Seleccione...</option><option label="4525979" value="380552">4525979</option>
<option label="980412532" value="908865">980412532</option>
<option label="993574781" value="1016356">993574781</option>
</select><br /><label for="tipo_contacto" class="zpFormLabel">Tipo de Contacto:</label><select name="tipo_contacto" class="zpFormRequired" onchange="validar_tipo_contacto();"><option value="">Seleccione...</option><option label="A - Titular" value="1">A - Titular</option>
<option label="Y - Encargado" value="2">Y - Encargado</option>
<option label="E - Errado" value="3">E - Errado</option>
<option label="I - Fuera de servicio" value="4">I - Fuera de servicio</option>
<option label="N - No contestan" value="5">N - No contestan</option>
<option label="B - Ocupado" value="7">B - Ocupado</option>
<option label="O - Otro" value="8">O - Otro</option>
<option label="L - Mensaje" value="9">L - Mensaje</option>
<option label="F - Familiar" value="6">F - Familiar</option>
</select><br /></div><label for="observaciones" class="zpFormLabel">Observaci&oacute;n:</label><textarea name="observaciones" class="zpFormNotRequired" cols="50" rows="4"></textarea><input type="hidden" name="fechasistema" value="" /><br /><!--<label for="resultado" class="zpFormLabel">Resultado Gestión:</label><select name="resultado" class="zpFormRequired" onchange="activarcompromiso();"><option value="">Seleccione...</option><option value="1000-1-P">1000 - Contacto Efectivo - Compromiso de Pago</option><option value="1002-53-P">1002 - Contacto Efectivo - Compromiso Mes Entrante</option><option value="1003-55-P">1003 - Contacto Efectivo - Interesado en Condonación</option><option value="1005-27-P">1005 - Contacto Efectivo - Pago Total</option><option value="1006-28-P">1006 - Contacto Efectivo - Cuenta al Día</option><option value="1007-56-P">1007 - Contacto Efectivo - Interesado en Pago Total</option><option value="1019-29-N">1019 - Contacto Efectivo - Refinanciamiento en Trámite</option><option value="1021-30-P">1021 - Contacto Efectivo - Pago Parcial</option><option value="1022-70-P">1022 - Contacto Efectivo - Compromiso pago Parcial</option><option value="1023-71-N">1023 - Contacto Efectivo - Ya Pagó</option><option value="5002-45-P">5002 - Contacto Efectivo - Convenio de Pago</option><option value="5012-60-P">5012 - Contacto Efectivo - Entrega de Facturas</option><option value="5013-61-P">5013 - Contacto Efectivo - Confirmacion de Entrega</option><option value="5014-62-P">5014 - Contacto Efectivo - Cobranza Preventiva</option><option value="5018-73-P">5018 - Contacto Efectivo - Comunicado - RPP</option><option value="1001-2-N">1001 - Contacto No Efectivo - Mensaje a Terceros</option><option value="1004-3-N">1004 - Contacto No Efectivo - Contacto sin Compromiso</option><option value="1008-65-P">1008 - Contacto No Efectivo - Recordatorio de Pago</option><option value="1009-74-N">1009 - Contacto No Efectivo - Seguimiento</option><option value="1011-64-P">1011 - Contacto No Efectivo - Desacuerdo</option><option value="1014-6-N">1014 - Contacto No Efectivo - Dificultad de Pago</option><option value="1015-7-N">1015 - Contacto No Efectivo - Renuente</option><option value="1029-4-P">1029 - Contacto No Efectivo - Tercero se Responsabiliza</option><option value="3030-68-P">3030 - Contacto No Efectivo - Devolución</option><option value="4002-44-N">4002 - Contacto No Efectivo - Compromiso en trámite</option><option value="5016-67-N">5016 - Contacto No Efectivo - Observacion</option><option value="1010-10-N">1010 - De Baja - Cuenta Errada</option><option value="1017-8-P">1017 - De Baja - Titular Fallecido</option><option value="1030-32-P">1030 - De Baja - Dejar de Gestionar a Solicitud</option><option value="1013-19-N">1013 - Inubicable - Inubicable</option><option value="1018-54-N">1018 - No Contacto - No Contacto</option><option value="1024-72-N">1024 - No Contacto - Ilocalizado Call</option><option value="4001-36-P">4001 - No Contacto - Verificar en campo</option><option value="5008-52-N">5008 - No Contacto - Actualización de Datos</option><option value="5010-57-N">5010 - No Contacto - Busqueda Externa</option><option value="5011-58-N">5011 - No Contacto - Busqueda Avanzada</option><option value="5015-63-P">5015 - Pendiente - Documento en Transito</option><option value="5017-69-P">5017 - Pendiente - Envio de Carta</option></select><br /><div id="div_justificacion" style="visibility:hidden;position:absolute;"><label for="justificacion_a" class="zpFormLabel">Justificaci&oacute;n:</label><select name="justificacion_a" class="zpFormRequired"></select><br /></div><div id="resultados" style="visibility:hidden"><label for="fechacompromiso" class="zpFormLabel">F. Compromiso:</label><input type="text" class="zpFormRequired zpFormDate" name="fechacompromiso" id="fechacompromiso" value="" size="11" maxlength="10" onchange="cambio_fecha_compromiso();" /><input type="button" id="bcalendario1" value=" ... " /><script type="text/javascript">
											Calendar.setup(	{
											inputField : "fechacompromiso", // ID of the input field
											ifFormat : "%d/%m/%Y", // the date format
											button : "bcalendario1", // ID of the button
											weekNumbers : false,
											range : [1900, 2050] } );</script><br /><label for="moneda" class="zpFormLabel">Moneda:</label><input type="text" size="11" class="zpFormNotRequired" name="moneda" value="Dolares" disabled /><br /><label for="importecompromiso" class="zpFormLabel">Imp. Compromiso:</label><input type="text" class="zpFormRequired zpFormFloat" name="importecompromiso" id="importecompromiso" value="0.00" size="10" maxlength="15" style="text-align:right" /><br /><br /></div> --><label for="nuevatarea" class="zpFormLabel">Nueva Tarea</label><input type="checkbox" name="nuevatarea" value="si" onchange="nueva_tarea();" /></fieldset><div id="div_tarea" style="visibility:hidden;position:absolute;"><fieldset><legend>Tarea</legend><label for="fechatarea" class="zpFormLabel">Fecha:</label><input type="text" class="zpFormRequired zpFormDate" name="fechatarea" id="fechatarea" value="05/01/2011" size="11" maxlength="10" /><input type="button" id="bcalendario3" value=" ... " /> (dd/mm/aaaa)<script type="text/javascript">
                                Calendar.setup(	{
                                    inputField : "fechatarea", // ID of the input field
                                    ifFormat : "%d/%m/%Y", // the date format
                                    button : "bcalendario3", // ID of the button
                                    weekNumbers : false,
                                    range : [1900, 2050] } );
                                </script><br /><label for="horatarea" class="zpFormLabel">Hora:</label><input type="text" name="horatarea" value="09:00" size="6" maxlength="5" class='zpFormRequired zpFormHour zpFormMask="00:00"' /> (HH:mm)<br /><label for="comentariotarea" class="zpFormLabel">Comentarios:</label><input type="text" name="comentariotarea" class="zpFormNotRequired" size="75" maxlength="100" value=""/><br /></fieldset></div><div class="zpFormButtons"><input type="hidden" name="acc" value="1" /><input type="hidden" name="codigo_form" value="20110105134713" /><input type="hidden" name="negid1" value="798" /><input type="hidden" name="tarea_incumplida" value="no" /><input type="submit" value="Aceptar" /></div></form>						</td></tr></table><table><tr><td><b style="color:#FF0000">Tarea Pendiente:</b> <b>Fecha:</b> 05/01/2011 09:00 <b>Comentarios:</b>  </td><td>&nbsp;&nbsp;&nbsp;<img src="images/ico_eliminar.gif" /></td><td><a href="javascript:void(0);" onclick="eliminar_tarea();"><b>Eliminar</b></a></td></tr></table></div></div><!-- /areaForm --><script>activarcompromiso();</script></div> <!-- Fin Gestion --><div class="TabbedPanelsContent"> <!-- Tareas --><div id="areaForm"><fieldset><legend>Tareas:</legend><form name="frmDatos4" id="userForm4" class="zpForm" method="post" style="text-align:left"><label for="fechatarea" class="zpFormLabel">Fecha:</label><input type="text" class="zpFormRequired zpFormDate" name="fechatarea" id="fechatarea" value="05/01/2011" size="11" maxlength="10" /><input type="button" id="bcalendario3" value=" ... " /> (dd/mm/aaaa)<script type="text/javascript">
					Calendar.setup(	{
						inputField : "fechatarea", // ID of the input field
						ifFormat : "%d/%m/%Y", // the date format
						button : "bcalendario3", // ID of the button
						weekNumbers : false,
						range : [1900, 2050] } );
					</script><br /><label for="horatarea" class="zpFormLabel">Hora:</label><input type="text" name="horatarea" value="09:00" size="6" maxlength="5" class='zpFormRequired zpFormHour zpFormMask="00:00"' /> (HH:mm)<br /><label for="comentariotarea" class="zpFormLabel">Comentarios:</label><input type="text" name="comentariotarea" class="zpFormNotRequired" size="75" maxlength="100" value=""/><br /><br /><div class="zpFormButtons"><input type="hidden" name="codigo_form" value="20110105134713" /><input type="hidden" name="cliid" value="438602" /><input type="hidden" name="negid1" value="798" /><input type="hidden" name="acc1" value="tar" /><input type="submit" value="Grabar" /><input type="button" value="Eliminar" onclick="eliminar_tarea();" /></div></form></fieldset></div></div> <!-- Fin Tareas --><div class="TabbedPanelsContent"> <!-- Direcciones --><div id="areaForm"><fieldset><legend>Nueva Dirección</legend><form name="frmDatos2" id="userForm2" class="zpForm" method="post" style="text-align:left"><label for="origen" class="zpFormLabel">Origen:</label><select name="origen" class="zpFormRequired" id="origen" ><option value="">Seleccione...</option><option label="Banco" value="8">Banco</option>
<option label="Casa" value="1">Casa</option>
<option label="Familiares" value="5">Familiares</option>
<option label="Oficina" value="3">Oficina</option>
<option label="Otros" value="4">Otros</option>
</select><br /><label for="priorizacion" class="zpFormLabel">Priorización:</label><select name="priorizacion" class="zpFormRequired" onchange="ver_priorizacion('direccion');"><option value="">Seleccione...</option><option label="Primario" value="1">Primario</option>
<option label="Secundario" value="2">Secundario</option>
</select><br /><label for="tipo" class="zpFormLabel">Tipo:</label><select name="tipo" class="zpFormRequired" ><option value="">Seleccione...</option><option label="Activo" value="1">Activo</option>
<option label="Inactivo" value="2">Inactivo</option>
</select><br /><label for="departamento" class="zpFormLabel">Departamento:</label><select name="departamento" class="zpFormRequired" onchange="buscar_provincias('frmDatos2');" ><option value="">Seleccione...</option><option label="AMAZONAS" value="01">AMAZONAS</option>
<option label="ANCASH" value="02">ANCASH</option>
<option label="APURIMAC" value="03">APURIMAC</option>
<option label="AREQUIPA" value="04">AREQUIPA</option>
<option label="AYACUCHO" value="05">AYACUCHO</option>
<option label="CAJAMARCA" value="06">CAJAMARCA</option>
<option label="CALLAO" value="07">CALLAO</option>
<option label="CUSCO" value="08">CUSCO</option>
<option label="HUANCAVELICA" value="09">HUANCAVELICA</option>
<option label="HUANUCO" value="10">HUANUCO</option>
<option label="ICA" value="11">ICA</option>
<option label="JUNIN" value="12">JUNIN</option>
<option label="LA LIBERTAD" value="13">LA LIBERTAD</option>
<option label="LAMBAYEQUE" value="14">LAMBAYEQUE</option>
<option label="LIMA" value="15">LIMA</option>
<option label="LORETO" value="16">LORETO</option>
<option label="MADRE DE DIOS" value="17">MADRE DE DIOS</option>
<option label="MOQUEGUA" value="18">MOQUEGUA</option>
<option label="PASCO" value="19">PASCO</option>
<option label="PIURA" value="20">PIURA</option>
<option label="PUNO" value="21">PUNO</option>
<option label="SAN MARTIN" value="22">SAN MARTIN</option>
<option label="TACNA" value="23">TACNA</option>
<option label="TUMBES" value="24">TUMBES</option>
<option label="UCAYALI" value="25">UCAYALI</option>
</select><br /><label for="provincia" class="zpFormLabel">Provincia:</label><select name="provincia" class="zpFormRequired" onchange="buscar_distrito('frmDatos2');"><option value="">Seleccione...</option></select><br /><script>//buscar_provincias();</script><label for="distrito" class="zpFormLabel">Distrito:</label><select name="distrito" class="zpFormRequired" onchange="buscar_cuadrante('frmDatos2');"/><option value="">Seleccione...</option></select><br /><label for="direccion" class="zpFormLabel">Direcci&oacute;n:</label><input type="text" name="direccion" class="zpFormRequired" value="" size="75" maxlength="255" /><input type="button" value="Buscar cuadrante..." name="busqueda_cuadrante" onclick="cuadrantes();" disabled /><br /><label for="cuadrante" class="zpFormLabel">Cuadrante:</label><select name="cuadrante" class="zpFormRequired" /><option value="">Seleccione...</option></select><br /><label for="observacion" class="zpFormLabel">Observaci&oacute;n:</label><input type="text" name="observacion" class="zpFormNotRequired" value="" size="75" maxlength="100" /><br /><div class="zpFormButtons"><input type="hidden" name="codigo_form" value="20110105134713" /><input type="hidden" name="cliid" value="438602" /><input type="hidden" name="acc1" value="dir" /><input type="submit" value="Aceptar" /></div></form></fieldset></div></div> <!-- Fin Direcciones --><div class="TabbedPanelsContent"> <!-- Telefonos --><div id="areaForm"><fieldset><legend>Nuevo Tel&eacute;fono:</legend><form name="frmDatos3" id="userForm3" class="zpForm" method="post" style="text-align:left"><label for="numerotelefono" class="zpFormLabel" >N&deg; tel&eacute;fono:</label><input type="text" class="zpFormRequired" name="numerotelefono" value="" size="15" maxlength="15" autocomplete="off" onchange="buscar_telefono();" /> <span id="span_mensaje_telefono" style="color:#FF0000;visibility:hidden;"></span><br /><label for="origen" class="zpFormLabel" >Origen:</label><select name="origentelefono" class="zpFormRequired" id="origentelefono"><option value="">Seleccione...</option><option label="Banco" value="8">Banco</option>
<option label="Casa" value="1">Casa</option>
<option label="Movil" value="2">Movil</option>
<option label="Oficina" value="3">Oficina</option>
<option label="Otros" value="4">Otros</option>
</select><br /><label for="priorizacion" class="zpFormLabel">Priorización:</label><select name="priorizacion" class="zpFormRequired" onchange="ver_priorizacion('telefono');"><option value="">Seleccione...</option><option label="Primario" value="1">Primario</option>
<option label="Secundario" value="2">Secundario</option>
</select><br /><label for="tipo" class="zpFormLabel" >Tipo:</label><select name="tipotelefono" class="zpFormRequired" id="tipotelefono"><option value="">Seleccione...</option><option label="Activo " value="1">Activo </option>
<option label="Inactivo" value="2">Inactivo</option>
</select><br /><label for="direcciontelefono" class="zpFormLabel" >Dirección:</label><select name="direcciontelefono" class="zpFormNotRequired"><option value="NULL">Sin Dirección</option><option value="307568">Calle Jaspampa 209</option><option value="944816">Calle Jaspampa 209 Tarapaca Callao</option><option value="944815">Calle Jaspampa 209Tarapaca Callao</option></select><br /><label for="observaciontelefono" class="zpFormLabel" >Observaci&oacute;n:</label><input type="text" name="observaciontelefono" class="zpFormNotRequired" size="75" maxlength="100" value="" /><br /><div class="zpFormButtons"><input type="hidden" name="codigo_form" value="20110105134713" /><input type="hidden" name="cliid" value="438602" /><input type="hidden" name="acc1" value="tel" /><input type="submit" value="Aceptar" /></div></form></fieldset></div></div> <!-- Fin Telefonos --><div class="TabbedPanelsContent"> <!-- Contactos --><div id="areaForm"><fieldset><legend>Nuevo Contacto:</legend><form name="frmDatos5" id="userForm5" class="zpForm" method="post" style="text-align:left"><label for="apellidopaterno" class="zpFormLabel">Ap. Paterno:</label><input type="text" class="zpFormRequired" name="apellidopaterno" value="" size="15" maxlength="15" /><br /><label for="apellidomaterno" class="zpFormLabel">Ap. Materno:</label><input type="text" class="zpFormNotRequired" name="apellidomaterno" value="" size="15" maxlength="15" /><br /><label for="nombres" class="zpFormLabel">Nombres:</label><input type="text" class="zpFormRequired" name="nombres" value="" size="20" maxlength="20" /><br /><label for="email" class="zpFormLabel">Email:</label><input type="text" class="zpFormRequired" name="email" value="" size="50" maxlength="50" /><br /><table><tr><td><label for="telefono" class="zpFormLabel">Teléfono:</label><input type="text" class="zpFormNotRequired zpFormInt" name="telefono" value="" size="15" maxlength="15" /></td><td><label for="anexo" class="zpFormLabel">Anexo:</label><input type="text" class="zpFormNotRequired zpFormInt" name="anexo" value="" size="6" maxlength="6" /></td></tr></table><br /><label for="tipodoc" class="zpFormLabel">Tipo Documento:</label><select name="tipodoc" class="zpFormRequired" onchange="activar();"><option value="">Seleccione...</option><option label="D.N.I. " value="1">D.N.I. </option>
<option label="R.U.C." value="2">R.U.C.</option>
<option label="C.I.P." value="3">C.I.P.</option>
<option label="Otros" value="4">Otros</option>
</select><br /><label for="dni" class="zpFormLabel">N° Documento:</label><input type="text" class="zpFormNotRequired" name="dni" value="" onKeydown="activar2();" /><br /><label for="parentesco" class="zpFormLabel">Parentesco:</label><select name="parentesco" class="zpFormRequired"><option value="">Seleccione...</option><option label="Amistad" value="2">Amistad</option>
<option label="Aval" value="4">Aval</option>
<option label="Conyuge" value="8">Conyuge</option>
<option label="Familiar" value="1">Familiar</option>
<option label="Laboral" value="3">Laboral</option>
<option label="Nadie" value="9">Nadie</option>
<option label="Otros" value="5">Otros</option>
<option label="Titular" value="6">Titular</option>
</select><br /><label for="cargo" class="zpFormLabel">Cargo:</label><select name="cargo" class="zpFormRequired"><option value="">Seleccione...</option><option label="Contabilidad" value="3">Contabilidad</option>
<option label="Gerencia" value="2">Gerencia</option>
<option label="Ninguno" value="1">Ninguno</option>
<option label="Pago a Proveedores" value="6">Pago a Proveedores</option>
<option label="Recepcion" value="5">Recepcion</option>
<option label="Tesoreria" value="4">Tesoreria</option>
</select><br /><label for="direccion" class="zpFormLabel">Dirección:</label><textarea name="direccion" class="zpFormRequired" cols="25" rows="6"></textarea><br /><label for="observacion" class="zpFormLabel">Comentarios:</label><textarea name="observacion" class="zpFormNotRequired" cols="25" rows="3"></textarea><br /><div class="zpFormButtons"><input type="hidden" name="codigo_form" value="20110105134713" /><input type="hidden" name="cliid" value="438602" /><input type="hidden" name="acc1" value="con" /><input type="submit" value="Aceptar" /></div></form></fieldset></div></div> <!-- Fin Contactos --></div></div>
<script type="text/javascript"> 
	var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1", {defaultTab:1});
</script>
</div>
<script> 
	var tables = document.getElementsByTagName("table");
	if (in_array("tabla_cuentas", tables, "id"))
	{
		ConvertRowsToLinks("tabla_cuentas");
	}
	mostrar_gestiones("438602", "", "call");
	mostrar_gestiones("438602", "", "campo");
</script>
</div> <!-- /ykBody -->
		<script type="text/javascript">
			Zapatec.Form.setupAll ({
				showErrors: "afterField",
				showErrorsOnSubmit: true
			});
		</script>
	</div><!-- /ykMain --></body></html>
