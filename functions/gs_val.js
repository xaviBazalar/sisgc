function gs_dir(){
var ori = document.getElementById('g_od').value
var prio = document.getElementById('g_pd').value
var tipo = document.getElementById('g_td').value
var dpto= document.getElementById('dpto').value
var prov = document.getElementById('prov').value
var dist = document.getElementById('dist').value
var cdr = document.getElementById('g_cd').value
dir = document.getElementById('g_dd').value
obs = document.getElementById('g_od').value
var id = document.getElementById('id_cli_gs').value

	if ( ori=="" || prio==""  || tipo==""  || dpto==""  || prov==""  || dist==""  || dir=="" || cdr=="" ){
	return false;
	}

	ajax=Ajax();
			ajax.open("GET", "functions/gestiones.php?id="+id+"&ori="+ori+"&&prio="+prio+"&&tipo="+tipo+"&&dpto="+dpto+"&&prov="+prov+"&&dist="+dist+"&&dir="+dir+"&&obs="+obs+"&&cdr="+cdr,true);
			ajax.onreadystatechange=
				function() {
					if (ajax.readyState==4) {
						
						if(ajax.responseText=="ok"){
							alert("see")
							document.location.reloaded();
						}
					}
				}
			ajax.send(null)
}