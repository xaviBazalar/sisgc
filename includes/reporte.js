var pag = 0;
var periodo = "";
var proveedor = "";

function reporte_cron(total)
{
	if (total == 0)
	{
		alert("No hay registros");
		finalizar(0);
		clearInterval(i_reporte_cron);
	}
	else
	{
		var url = "includes/ws/avance.php";
		var myAjax = new Ajax.Request( url, {method: "get", parameters: "", onComplete: function(originalRequest)
			{
				var avance = eval(originalRequest.responseText);
				if (avance[0] == "0")
				{
					finalizar(1);
					clearInterval(i_reporte_cron);
				}
				else
				{
					if (avance[0] == -1)
					{
						avance[1] = "0.00";
					}
					avanzado = avance[1] * 4;
					pendiente = 400 - avanzado;
					porcentaje_avance(avanzado, pendiente, avance[1]);
				}
			}
		});
	}
}

function reporte(total)
{
	setTimeout("", 5000);
	var url = "includes/ws/r_foto_cartera.php";
	var pars = "pag=" + pag + "&total=" + total;
	var myAjax = new Ajax.Request( url, {method: "get", parameters: pars,
		onComplete: function(originalRequest)
		{
			var avance = eval(originalRequest.responseText);
			pag = pag + 1;
			if (total == "")
			{
				total = avance[0];
			}
			if (total == 0)
			{
				alert("No hay registros");
				finalizar(0);
			}
			else
			{
				avanzado = avance[1] * 4;
				pendiente = 400 - avanzado;
				porcentaje_avance(avanzado, pendiente, avance[1]);
				if ((avance[0] * 1) < (total * 1) || pag == 1)
				{
					reporte(total);
				}
				else
				{
					finalizar(1);
				}
			}
		}
	} );
}

function finalizar(estado)
{
	var obj = document.frmDatos;
	total = "";
	pag = 0;
	periodo = "";
	proveedor = "";
	if (estado == "1")
	{
		self.location.href = "bajar.php?nombre=foto_cartera";
	}
	mostrar_barra("hidden");
	porcentaje_avance(0, 400, "0.00");
	reporte_finalizado();
}

function mostrar_barra(mostrar)
{
	if (document.all) {
		tr_barra.setAttribute("style", "visibility:" + mostrar + ";"); }
	else if (document.layers) {
		document.tr_barra.setAttribute("style", "visibility:" + mostrar + ";");
	}
	else if (document.getElementById) {
		document.getElementById("tr_barra").setAttribute("style", "visibility:" + mostrar + ";"); }
}

function porcentaje_avance(avanzado, pendiente, porcentaje)
{
	avance = "<img src='images/colores/4.gif' align='absmiddle' height='15' width='" + avanzado + "'><img src='images/colores/2.gif' align='absmiddle' height='15' width='" + pendiente + "'> <b>" + porcentaje;
	if (document.all) {
		span_avance.innerHTML = avance; }
	else if (document.layers) {
		document.span_avance.innerHTML = avance; }
	else if (document.getElementById) {
		document.getElementById("span_avance").innerHTML = avance; }
}