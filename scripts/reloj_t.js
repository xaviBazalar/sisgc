


function relojW()
{
/*fecha = new Date()

hora = fecha.getHours()
if (hora>12) {
meri=' pm';
//hora=hora-12;
}
else
meri=' am';
minuto = fecha.getMinutes()
if (minuto<10) minuto='0'+minuto;
segundo = fecha.getSeconds()
if (segundo<10) segundo='0'+segundo;
horita = hora + ":" + minuto + ":" + segundo + meri;
document.getElementById('hora').innerHTML = "Hora : "+horita*/

ajax=Ajax();
			ajax.open("GET", "functions/hora.php",true);
			ajax.onreadystatechange=
				function() {
					if (ajax.readyState==4) {
							document.getElementById('hora').innerHTML  = ajax.responseText
					}
				}
ajax.send(null)

setTimeout('relojW()',1000)

}

