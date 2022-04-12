

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

	function cambiarClase()  {
		document.getElementById('oc').className="none";
		document.getElementById('oc').display="block";
	}

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
			document.getElementById("mas_menos").innerHTML = "(-)";
		}
		else {
			estilos[estilos.length - 1].style.display = "none";
			estilos[estilos.length - 2].style.display = "none";
			document.getElementById("mas_menos").innerHTML = "(+)";
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

	arr_telefonos = new Array('044316352');
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
		var pars = "id=627546&u=" + horario;
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
		document.getElementById("span_gestiones_"+capa).style.visibility = "hidden";
		document.getElementById("span_gestiones_"+capa).style.position = "absolute";
		document.getElementById("span_gestiones_"+capa1).style.visibility = "visible";
		document.getElementById("span_gestiones_"+capa1).style.position = "relative";
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