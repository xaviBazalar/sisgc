function zonif_fil(){
	var iden =document.getElementById("iden").value
	var dpto =document.getElementById("dpto").value
	var prov =document.getElementById("prov").value
	var dist =document.getElementById("dist").value
	location.href='index.php?tipo=plano&parametros='+iden+'&&pag=1&&coddpto='+dpto+'&codprov='+prov+'&coddist='+dist

}