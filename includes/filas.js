var fila;
var r_id = "";

function ConvertRowsToLinks(xTableId) {
    var rows = document.getElementById(xTableId).rows;
    for (i = 0; i < rows.length; i++) {
        var event = "";
        if(rows[i].innerHTML.match("nohighlight") == null) {
            rows[i].onmouseover = new Function("this.className='row_highlight'");
            rows[i].onmouseout = new Function("perder_foco(this);");

			if(rows[i].innerHTML.match("nolink")) {
			} else {
				rows[i].onclick =  new Function("seleccionar_fila(this);");
			}
		}
    }
}

function perder_foco(r) {
	if (r != fila) {
		r.className = ""; }
	else {
		if (r.className = "row_highlight") {
			r.className = "row_click";
		}
	}
}

function seleccionar_fila(r) {
	r_id = r.id;
	if (fila != undefined) {
		fila.className = ""; }
	fila = r;
	r.className = "row_click";

	var rows = document.getElementById("tabla_cuentas_detalle").rows;
	for (i = 0; i < rows.length; i++) {
		if (rows[i].id == r.id) {
			rows[i].className = "row_click"; }
		else {
			rows[i].className = ""; }
	}

	var rows1 = document.getElementById("tabla_gestiones_call").rows;
	for (i = 0; i < rows1.length; i++) {
		if (rows1[i].id == r.id) {
			rows1[i].className = "row_click"; }
		else {
			rows1[i].className = ""; }
	}

	var rows1 = document.getElementById("tabla_gestiones_campo").rows;
	for (i = 0; i < rows1.length; i++) {
		if (rows1[i].id == r.id) {
			rows1[i].className = "row_click"; }
		else {
			rows1[i].className = ""; }
	}

	var rows2 = document.getElementById("tabla_pagos").rows;
	for (i = 0; i < rows2.length; i++) {
		if (rows2[i].id == r.id) {
			rows2[i].className = "row_click"; }
		else {
			rows2[i].className = ""; }
	}
}

function in_array(needle, haystack, attribute) {
    for (var i = 0; i < haystack.length; i++) {
        if (eval("haystack[i]." + attribute + ";") == needle) {
            return true;
        }
    }
    return false;
}