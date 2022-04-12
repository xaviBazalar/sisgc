function sel_ubi(){
	if(confirm("Seguro de cambiar la hora de ubicabilidad?")){
	
		var  id=document.getElementById("idcliente").value
		var  ubi=document.getElementById("ubicabilidad_c").value
		obj =document.getElementById("ubicabilidad_c");

		
		ajax=Ajax();
		ajax.open("GET", "functions/sel_ubi_cli.php?id="+id+"&ubi="+ubi,true);
		ajax.onreadystatechange=
			function() {
				if (ajax.readyState==4) {
					
				}
			}
		ajax.send(null)
		
	}else{
		var  id=document.getElementById("idcliente").value
		obj =document.getElementById("ubicabilidad_c");
	
		
		ajax=Ajax();
		ajax.open("GET", "functions/sel_ubi_cli.php?id="+id,true);
		ajax.onreadystatechange=
			function() {
				if (ajax.readyState==4) {
					for(i=0;i<obj.length;i++){
						if(obj.options[i].value==ajax.responseText){
							obj.options[i].selected=true;
						}
					
					}
				}
			}
		ajax.send(null)
	
	}
}


function liberar(){
	  id_pr=document.getElementById("id_user_p").value
	  id_ll=document.getElementById("idllam").value
	  ajax=Ajax();
      ajax.open("POST", "http://192.168.50.16/o8/webserv/libera.php",true);
      ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");	//POST
      var datos="id="+id_pr+"&idllam="+id_ll;
      ajax.send(datos);          													//parametros
	  ajax.onreadystatechange=
	  	function() {
	  		if (ajax.readyState==4) {
				
	  		}
	  	}

}
function pop_up(web){
	idcli= document.getElementById('id_cli_gs').value
	cta=document.getElementById('cuenta2').value
	ventana = window.open("functions/"+web+".php?idcliente="+idcli+"&cta="+cta, "ventana_busqueda", "height=400,width=500,status=yes,toolbar=no,scrollbars=yes,menubar=no,location=no");
}


function contactos_rpp(){
	idcli= document.getElementById('id_cli_gs').value
	ventana = window.open("functions/contactos_rpp.php?idcliente="+idcli, "ventana_busqueda", "height=400,width=500,status=yes,toolbar=no,scrollbars=yes,menubar=no,location=no");
}


function valp1(){
			var p2= document.getElementById('p1').value
			
			if(p2==3 || p2==4){
				document.getElementById("box").style.visibility="visible";
				document.getElementById("box").style.position="relative";
			}else{
				 document.getElementById("box").style.visibility="hidden";
				 document.getElementById("box").style.position="absolute";
			}
	
}

function mail(){
	window.open('functions/Email/index.php', 'Envio Mail', 'height=500,width=600,status=yes,toolbar=no,scrollbars=yes,menubar=no,location=no');
}

function tv_box(){
	var ob_rgs=document.getElementById("det_ges").value
	rgs=document.getElementById("resultado_gs").value
	rgs=rgs.split("-")

	if(ob_rgs=="225") {
		document.getElementById('ventas_tarjeta').style.visibility='visible'
		document.getElementById('ventas_tarjeta').style.position='relative'
		document.getElementById('ventas_cita').style.visibility='hidden'
		document.getElementById('ventas_cita').style.position='absolute'
	}else if(ob_rgs=="226") {
		document.getElementById('ventas_cita').style.visibility='visible'
		document.getElementById('ventas_cita').style.position='relative'
		document.getElementById("ventas_tarjeta").style.visibility = "hidden";
		document.getElementById("ventas_tarjeta").style.position = "absolute";
	}else{
		document.getElementById('ventas_cita').style.visibility='hidden'
		document.getElementById('ventas_cita').style.position='absolute'	
		document.getElementById("ventas_tarjeta").style.visibility = "hidden";
		document.getElementById("ventas_tarjeta").style.position = "absolute";
	}
	
	
	

	if(ob_rgs=="296" || ob_rgs=="297" || ob_rgs=="321" || ob_rgs=="322" || ob_rgs=="323" || rgs[1]=="248"  || rgs[1]=="2"  ){
		document.getElementById('new_t').checked=true;
		document.getElementById('new_t').style.visibility='hidden'
		document.getElementById('new_t').style.position='absolute'
		document.getElementById('div_tarea').style.visibility='visible'
		document.getElementById('div_tarea').style.position='relative'
		document.getElementById('resultados').style.visibility='visible'
		document.getElementById('resultados').style.position='relative'
		
	}else if(rgs[1]=="254" || rgs[1]=="1"){
		document.getElementById('resultados').style.visibility='visible'
		document.getElementById('resultados').style.position='relative'
		document.getElementById('new_t').checked=false;
		document.getElementById('new_t').style.visibility='visible'
		document.getElementById('new_t').style.position='relative'
		document.getElementById('div_tarea').style.visibility='hidden'
		document.getElementById('div_tarea').style.position='absolute'
	
	}else{
		document.getElementById('new_t').checked=false;
		document.getElementById('new_t').style.visibility='visible'
		document.getElementById('new_t').style.position='relative'
		document.getElementById('div_tarea').style.visibility='hidden'
		document.getElementById('div_tarea').style.position='absolute'
		document.getElementById('resultados').style.visibility='hidden'
		document.getElementById('resultados').style.position='absolute'

	}
}

function val_d(){
	var val=document.getElementById("val_cta").value
	if(val==1){
		document.getElementById("val_det").style.visibility = "visible";
		document.getElementById("val_det").style.position = "relative";
	}else{
		document.getElementById("val_det").style.visibility = "hidden";
		document.getElementById("val_det").style.position = "absolute";
	}

}

function in_user(){
			var web=document.getElementById("ws").value;
			if(web==""){
				return true;
			}else{
				ajax=Ajax();
				ajax.open("GET",web,true);

				ajax.onreadystatechange=
					function() {
					if (ajax.readyState==4) {
						divResultado.innerHTML = ajax.responseText
					}
				}
				ajax.send(null)
				return true;
			}
}

function priorizacion(cli, id, tipo) {
		var obj = document.getElementById(id);
		var cancelar = 0;
		var vtipo;
		
		
		if(tipo=="dir"){
			priod=document.getElementsByName("dprioridad")
			url="functions/estado_dir.php?iddir="+id+"&idcli="+cli;
			vtipo = "esta direccion"; 
		}
		if(tipo=="tel"){
			priod=document.getElementsByName("tprioridad")
			url="functions/estado_tel.php?idtel="+id+"&idcli="+cli;
			vtipo = "este telefono"; 
		}
		
		var radioLength = priod.length;
		for(var i = 0; i < radioLength; i++) {
			if(priod[i].value==1){
				var prid=priod[i].getAttribute("id")
				break;
			}

		}

		if (obj.checked) {
			if (confirm("¿Esta seguro de establecer la priorizacion de " + vtipo + " como primaria?")) {
				
				ajax=Ajax();
					ajax.open("GET",url ,true);

					ajax.onreadystatechange=
						function() {
							if (ajax.readyState==4) {		}
						}
				ajax.send(null)

					document.getElementById(id).value='1';
					document.getElementById(prid).value='0';

				var estado;
			}else {
				cancelar = 1;
				document.getElementById(prid).checked=true;
			}	
		}
		

}
//---------------------------------------------------------------------
	function s_acrs(datos) {
		var cart = eval(datos);

			var obj = document.getElementById('resultado_gs');

			obj.length = 0;
			obj.options[0] = new Option('--Seleccionar--', '');
			for(contador = 0; contador < cart.length; contador++) {
				obj.options[contador + 1] = new Option(cart[contador][1], cart[contador][0]);
			}
	}
	
function s_fono(){
	var ind=document.getElementById("g_ig").value
	if(ind>=10 || ind==""){
		ajax=Ajax();
					ajax.open("GET","functions/sel_ac_rs.php?idac="+ind ,true);

					ajax.onreadystatechange=
						function() {
							if (ajax.readyState==4) {	
								datos=ajax.responseText;
								if(datos==""){
									var obj = document.getElementById('resultado_gs');

									obj.length = 0;
									obj.options[0] = new Option('--Seleccionar--', '');
								}else{
									s_acrs(datos)	
								}
							}
						}
		ajax.send(null)
		return false;
	}
		if(ind==9){
			document.getElementById("show_j").style.visibility = "hidden";
			document.getElementById("show_j").style.position = "absolute";
			document.getElementById("show_rg").style.visibility = "hidden";
			document.getElementById("show_rg").style.position = "absolute";
			document.getElementById("show_dir").style.visibility = "visible";
			document.getElementById("show_dir").style.position = "relative";
			
		}else{
			document.getElementById("show_j").style.visibility = "visible";
			document.getElementById("show_j").style.position = "relative";
			document.getElementById("show_rg").style.visibility = "visible";
			document.getElementById("show_rg").style.position = "relative";
			document.getElementById("show_dir").style.visibility = "hidden";
			document.getElementById("show_dir").style.position = "absolute";
			
		}
		if(ind==3 || ind==9){
			document.getElementById("telf_gestion").style.visibility = "hidden";
			document.getElementById("telf_gestion").style.position = "absolute";
			document.getElementById("tipc_gestion").style.visibility = "hidden";
			document.getElementById("tipc_gestion").style.position = "absolute";
			document.getElementById("dat_val").style.visibility = "hidden";
			document.getElementById("dat_val").style.position = "absolute";
			document.getElementById("dat_email").style.visibility = "hidden";
			document.getElementById("dat_email").style.position = "absolute";
			
		}else {
			document.getElementById("telf_gestion").style.visibility = "visible";
			document.getElementById("telf_gestion").style.position = "relative";
			document.getElementById("tipc_gestion").style.visibility = "visible";
			document.getElementById("tipc_gestion").style.position = "relative";
			document.getElementById("dat_val").style.visibility = "visible";
			document.getElementById("dat_val").style.position = "relative";
		}
		
		if(ind==6 || ind==9){
			document.getElementById("telf_gestion").style.visibility = "hidden";
			document.getElementById("telf_gestion").style.position = "absolute";
			document.getElementById("dat_val").style.visibility = "hidden";
			document.getElementById("dat_val").style.position = "absolute";
			document.getElementById("dat_email").style.visibility = "hidden";
			document.getElementById("dat_email").style.position = "absolute";
		}else if(ind!=3){
			document.getElementById("telf_gestion").style.visibility = "visible";
			document.getElementById("telf_gestion").style.position = "relative";
			document.getElementById("dat_val").style.visibility = "visible";
			document.getElementById("dat_val").style.position = "relative";
			document.getElementById("dat_email").style.visibility = "visible";
			document.getElementById("dat_email").style.position = "relative";
		}
		
		if(ind==7){
			document.getElementById("dat_email").style.visibility = "visible";
			document.getElementById("dat_email").style.position = "relative";
			document.getElementById("telf_gestion").style.visibility = "hidden";
			document.getElementById("telf_gestion").style.position = "absolute";
			
			
		}else if(ind!=3 & ind!=6 & ind!=9) {
			document.getElementById("telf_gestion").style.visibility = "visible";
			document.getElementById("telf_gestion").style.position = "relative";
			document.getElementById("dat_email").style.visibility = "hidden";
			document.getElementById("dat_email").style.position = "absolute";
			
		}


}

function show_cta_gest(cta){
	var cts=document.getElementById(cta)
	var gest_c=document.getElementById("tabla_cuentas").childNodes;
	
	var	nod2=gest_c[5].childNodes//Elijo el tbody
	var	totl2=nod2.length//Hijos del tbody
		
		for(i=0;i<=totl2;i++){
			if(nod2[i].nodeName=="TR"){
				if(nod2[i].getAttribute("id")==cta){
					nod2[i].className="row_click";
				}else{
					nod2[i].className="";
				}
			}else{
				if(i==(totl2-1)){
					break;//romper el for depues de leer los nodos y asi continuar con el script
				}
			}
		}
	
		var gest_cts=document.getElementById("tabla_gestiones_call").childNodes;
		total_g_gts=gest_cts.length;
		nod=gest_cts[3].childNodes;//Elijo el tbody
		totl=nod.length;//Hijos del tbody
		for(i=0;i<=totl;i++){
			if(nod[i].nodeName=="TR"){
				if(nod[i].getAttribute("id")==cta){
					nod[i].className="row_click";
				}else{
					nod[i].className="";
				}
			}
		}
	
}

function nueva_tarea()	{
		if (document.frmDatos.nuevatarea.checked)
		{
			document.getElementById("div_tarea").style.visibility = "visible";
			document.getElementById("div_tarea").style.position = "relative";
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
			document.getElementById("div_tarea").style.visibility = "hidden";
			document.getElementById("div_tarea").style.position = "absolute";
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
	
function buscar(web){
		in_user();
		if(document.getElementById('ykBody')){
			var divResultado = document.getElementById('ykBody');
		}
		
		if(web!="importar.php"){
			if(document.getElementById('c_gestion') ){
				var divResultado = document.getElementById('c_gestion');
			}
		}
		
		if(document.getElementById('numdoc')){
			var n_doc=document.getElementById('numdoc').value
			if(n_doc!=""){
				var web=web+"&ndoc="+n_doc
			}
		}
		
		if(document.getElementById('r_cartera')){
			var r_cart=document.getElementById('r_cartera').value
			if(r_cart!=""){
				var web=web+"&r_cart="+r_cart
			}
			
		}
		
		if(document.getElementById('name_gs')){
				var name=document.getElementById('name_gs').value
			if(name!=""){
				var web=web+"&name="+name
			}
		}
		
		if(document.getElementById('grup')){
				var grup=document.getElementById('grup').value
			if(grup!=""){
				var web=web+"&grup="+grup
			}
		}
		if(document.getElementById('ciclo')){
				var ciclo=document.getElementById('ciclo').value
			if(ciclo!=""){
				var web=web+"&ciclo="+ciclo
			}
		}
		
		if(document.getElementById('u_prove')){
				var prov=document.getElementById('u_prove').value
			if(prov!=""){
				var web=web+"&prov="+prov
			}
		}
		
		if(document.getElementById('u_cart')){
				var cart=document.getElementById('u_cart').value
			if(cart!=""){
				var web=web+"&cart="+cart
			}
		
		}
		
		if(document.getElementById('u_clasi')){
				var clasificacion=document.getElementById('u_clasi').value
			if(clasificacion!=""){
				var web=web+"&clasificacion="+clasificacion
			}
		
		}
		
		if(document.getElementById('u_ries')){
				var riesgo=document.getElementById('u_ries').value
			if(riesgo!=""){
				var web=web+"&riesgo="+riesgo
			}
		
		}
		
		if(document.getElementById('u_tcart')){
				var tipo_ct=document.getElementById('u_tcart').value
			if(tipo_ct!=""){
				var web=web+"&tipo_cartera="+tipo_ct
			}
		
		}
			ajax=Ajax();
			ajax.open("GET",web,true);
			divResultado.innerHTML = "<center><img src='imag/cargando.gif'/></center>"

			ajax.onreadystatechange=
				function() {
				divResultado.innerHTML = "<center><img src='imag/cargando.gif'/></center>"
				if (ajax.readyState==4) {
					divResultado.innerHTML = ajax.responseText
				}
			}
			ajax.send(null)
			return;
	}

	function ver_gestiones(capa, capa1)	{	
		document.getElementById("span_gestiones_"+capa).style.visibility = "hidden";
		document.getElementById("span_gestiones_"+capa).style.position = "absolute";
		document.getElementById("span_gestiones_"+capa1).style.visibility = "visible";
		document.getElementById("span_gestiones_"+capa1).style.position = "relative";
	}

	function res_ges(){
	ob_rgs=document.getElementById("det_ges")
	rgs=document.getElementById("resultado_gs").value
	rgs=rgs.split("-")
	ajax=Ajax();
			ajax.open("GET","functions/sel_resultado.php?valor="+rgs[1],true);
			ajax.onreadystatechange=
				function() {
				if (ajax.readyState==4) {
					sxs=ajax.responseText
					dato = sxs.split(",")
							
					ob_rgs.length = 0;
					ob_rgs.options[0] = new Option('Seleccione..', '');
					for(contador = 0; contador < dato.length; contador++){
						var datos=dato[contador].split("-")		
							ob_rgs.options[contador + 1] = new Option(datos[0], datos[1]);
					}
				}
				
			}
	ajax.send(null)
	if(rgs[1]=="51") {
	
	}else if(rgs[0]=="1" || rgs[0]=="11"){
		if(rgs[1]=="2" || rgs[1]=="248" || rgs[1]=="359" || rgs[1]=="360" || rgs[1]=="361" || rgs[1]=="362" || rgs[1]=="363"){
			document.getElementById('new_t').checked=true;
			document.getElementById('new_t').style.visibility='hidden'
			document.getElementById('new_t').style.position='absolute'
			document.getElementById('div_tarea').style.visibility='visible'
			document.getElementById('div_tarea').style.position='relative'
		}else{
			document.getElementById('new_t').checked=false;
			document.getElementById('new_t').style.visibility='visible'
			document.getElementById('new_t').style.position='relative'
			document.getElementById('div_tarea').style.visibility='hidden'
			document.getElementById('div_tarea').style.position='absolute'
		
		}
		//if((rgs[0]=="1" || rgs[0]=="11") && (rgs[1]=="248" || rgs[1]=="254")){

		if(rgs[1]=="248" || rgs[1]=="254" || rgs[1]=="351" || rgs[1]=="2" || rgs[1]=="1" || rgs[1]=="359" || rgs[1]=="360" || rgs[1]=="361" || rgs[1]=="362" || rgs[1]=="363"){
			document.getElementById('resultados').style.visibility='visible'
			document.getElementById('resultados').style.position='relative'
		
				cuentas = document.getElementById('cuenta2').getElementsByTagName("option")
				var valt=cuentas.length
				
				var z=0;
				for(i=0;i<=valt;i++){
					if(i == valt && z<=1){
					   break;
					}
					
					if(cuentas[i].selected){
						++z
						if(z>=2){
							tipo_m_g()
						}
					}
				}
		}else{
		document.getElementById('new_t').checked=false;
		document.getElementById('new_t').style.visibility='visible'
		document.getElementById('new_t').style.position='relative'
		document.getElementById('div_tarea').style.visibility='hidden'
		document.getElementById('div_tarea').style.position='absolute'
		document.getElementById('resultados').style.visibility='hidden'
		document.getElementById('resultados').style.position='absolute'

		}
		
	}else{
		document.getElementById('new_t').checked=false;
		document.getElementById('new_t').style.visibility='visible'
		document.getElementById('new_t').style.position='relative'
		document.getElementById('div_tarea').style.visibility='hidden'
		document.getElementById('div_tarea').style.position='absolute'
		document.getElementById('resultados').style.visibility='hidden'
		document.getElementById('resultados').style.position='absolute'
	}
	
	if(rgs[1]==67){
		document.getElementById("encuesta_c").style.visibility="visible";
	}else{
		document.getElementById("encuesta_c").style.visibility="hidden";
	}
	
}

function gs_dir(){
	var ori = document.getElementById('g_od').value
	var prio = document.getElementById('g_pd').value
	var ft = 2	
	var dpto= document.getElementById('dpto').value
	var prov = document.getElementById('prov').value
	var dist = document.getElementById('dist').value
	var cdr = document.getElementById('g_cd').value
	dir = document.getElementById('g_dd').value
	var obs = document.getElementById('g_odobs').value
	var id = document.getElementById('id_cli_gs').value
	
	priod=document.getElementsByName('g_pds')
		var radioLength = priod.length;
		for(var i = 0; i < radioLength; i++) {
			if(priod[i].checked){
				var tipo = priod[i].value;
			}
		}
		
	if ( ori=="" || prio==""  || tipo==""  || dpto==""  || prov==""  || dist==""  || dir=="" || cdr=="" ){
		document.getElementById("e_d").innerHTML="<font color='red'>Faltan Datos</font>"
		return false;
	}
	var sURL = unescape(window.location.pathname);
		document.location.href="functions/gestiones.php?tipo=dir&id="+id+"&ori="+ori+"&&prio="+prio+"&&tipod="+tipo+"&&dpto="+dpto+"&&prov="+prov+"&&dist="+dist+"&&dir="+dir+"&&obs="+obs+"&&cdr="+cdr+"&ft="+ft;
}

function gs_tel(){
	idcliente=document.getElementById("idcliente").value
	var nrot = document.getElementById('g_nt').value
	var ot = document.getElementById('g_ot').value
	var ft = 2
		priot=document.getElementsByName('g_pts')
		var radioLength = priot.length;
		for(var i = 0; i < radioLength; i++) {
			if(priot[i].checked){
				var pt = priot[i].value;
			}
		}
	
	var tt= document.getElementById('g_tt').value
	var dt= document.getElementById('g_dt').value
	var obt = document.getElementById('g_obt').value
	var id = document.getElementById('id_cli_gs').value
	
	if ( nrot=="" || ot==""  || pt==""  || tt==""  || dt==""  ||  id=="" ){
		document.getElementById("e_t").innerHTML="<font color='red'>Faltan Datos</font>"
		return false;
	}
	var sURL = unescape(window.location.pathname);
		document.location.href="functions/gestiones.php?idcliente="+idcliente+"&tipo=tel&id="+id+"&nrot="+nrot+"&&o_t="+ot+"&&p_t="+pt+"&&t_t="+tt+"&&d_t="+dt+"&&ob_t="+obt+"&ft="+ft;

}

function gs_cont(){
	var pat = document.getElementById('g_apc').value
	var mat = document.getElementById('g_amc').value
	var nbr = document.getElementById('g_nc').value
	var mail= document.getElementById('g_ec').value
	var fono= document.getElementById('g_tc').value
	var anex = document.getElementById('g_tac').value
	var doi= document.getElementById('g_tdc').value
	var n_doc = document.getElementById('g_ndc').value
	var parent = document.getElementById('g_pc').value
	var obs = document.getElementById('g_oc').value
	var id = document.getElementById('id_cli_gs').value
	
	area="";flag=0;
	
	if(!document.getElementById('g_ar_ct')){
		if ( pat=="" || mat==""  || nbr==""  || mail==""  || fono==""  ||  doi=="" ||  n_doc=="" ||  parent=="" ){
			document.getElementById("e_c").innerHTML="<font color='red'>Faltan Datos</font>"
			return false;
		}
	}else{
		doi=1;
		flag=1;
		if(parent==""){parent=8;}
		area=document.getElementById('g_ar_ct').value;
		
	}
	
	var sURL = unescape(window.location.pathname);
	document.location.href="functions/gestiones.php?tipo=cont&id="+id+"&name="+pat+" "+mat+","+nbr+"&&mail="+mail+"&&fono="+fono+"&&anex="+anex+"&&doi="+doi+"&&nrod="+n_doc+"&&paren="+parent+"&&obs="+obs+"&flag="+flag+"&area="+area;

}

function prio_cli() {
     var elem = document.getElementsByName("tprioridad");
	 var tot = elem.length
	  alert(tot)
}

function tipo_m_g(value) {
	if(!value){
		document.getElementById('g_mg').value=""
	}
	if(value){
		var id = value.split("-")
		id = id[1]
	}
	
	rgs=document.getElementById("resultado_gs").getElementsByTagName("option")/*esto falla en ie*/
	rgs_t=rgs.length
	
	for(j=0;j<=rgs_t;j++){
		obs = rgs[j].value.split("-")
		if(rgs[j].selected){

			if(obs[0]=="1"){
				cuentas = document.getElementById('cuenta2').getElementsByTagName("option")
				var valt=cuentas.length
				
				var z=0;
				for(i=0;i<=valt;i++){
					if(i == valt && z<=1){
					   break;
					}
					
					if(cuentas[i].selected){
					++z
						if(z>=2){
							alert("Accion no permitida para Resultado Gestion : Ya pago y/o Promesa de Pago")
							alert("Seleccione solo una cuenta para este Resultado")
								for(i=0;i<=valt;i++){
									cuentas[i].selected=false
								}
							break;
						}
					}
				}
				
				break;
			}else{
				break;
			}
		}
		
	}

	ajax=Ajax();
			ajax.open("GET", "functions/ges_tipo_mon.php?moneda="+id,true);
			ajax.onreadystatechange=
				function() {
					if (ajax.readyState==4) {
						
						tipo_m= ajax.responseText.split(",")
						document.getElementById('g_mg').value=tipo_m[1]
					}
				}
	ajax.send(null)
}

function vertexto()	{
			var cb=document.getElementById('p3').value
			if(cb != 9)
			{
				document.getElementById("txt").style.visibility="hidden";
				document.getElementById("txt").style.position="absolute";	
			}
			else
			{
				document.getElementById("txt").style.visibility="visible";
				document.getElementById("txt").style.position="relative";
			}
			//------------------------------------------------------------------
			var cb1=document.getElementById('p1').value
			if(cb1 != 8)
			{
				document.getElementById("txt1").style.visibility="hidden";
				document.getElementById("txt1").style.position="absolute";	
			}
			else
			{
				document.getElementById("txt1").style.visibility="visible";
				document.getElementById("txt1").style.position="relative";
			}
			//--------------------------------------------------------------------
			var cb2=document.getElementById('p9').value
			if(cb2 != 6)
			{
				document.getElementById("txt2").style.visibility="hidden";
				document.getElementById("txt2").style.position="absolute";
			}
			else
			{
				document.getElementById("txt2").style.visibility="visible";
				document.getElementById("txt2").style.position="relative";
			}
		}
		
function valp2(){
			var p3= document.getElementById('p2').value
			
			if(p3==3 || p3==4){
				document.getElementById("box").style.visibility="visible";
				document.getElementById("box").style.position="relative";
			}else{
				 document.getElementById("box").style.visibility="hidden";
				 document.getElementById("box").style.position="absolute";
			}
}

function gs_gs_r(id){

		cuentas = document.getElementById('cuenta2').getElementsByTagName("option")
		var valt=cuentas.length
		if(valt=='0'){
			document.getElementById("e_gg").innerHTML="<font color='red'>No hay cuentas para gestionar</font>"
			return false;
		}
		var r_ges = document.getElementById('resultado_gs').value	
		validar=r_ges.split("-")
		if(validar[0]=="0"){
			var z=0;
			var total_cuentas=""	
				for(i=0,z=0;i<valt;i++){
						if(cuentas[i].selected){
							if(i==valt-1){
								if(z>=1){
									total_cuentas+=",";
								}
								total_cuentas+=cuentas[i].value;
								break;
							}
								if(z>=1){
									total_cuentas+=",";
								}
								total_cuentas+=cuentas[i].value;
							z++;
						}
				}
		}		
		var cuenta = document.getElementById('cuenta2').value
		var tf_ges = document.getElementById('g_tg').value
		if(id==1 || id==2){
			if(tf_ges==0 || tf_ges==''){
				alert("Elija un Telefono Por favor")
				return false;
			}
		}else if(id==3){
			if(tf_ges==''){
				alert("Elija un Telefono Por favor")
				return false;	
			}
		
		}
		var r_ges = document.getElementById('resultado_gs').value
				
		if(validar[0]=="0"){
			much_cuent=total_cuentas.split(",")
			total=much_cuent.length
			if(total>1){
				var cuenta=total_cuentas
			}
		}
				
		idcliente=document.getElementById("idcliente").value
		id_pr=document.getElementById("id_user_p").value
		var f_ges= document.getElementById('g_fg').value
		if(id==1){
			document.location.href="functions/gestiones.php?idcliente="+idcliente+"&tipo=gest&cta="+cuenta+"&fges="+f_ges+"&r_ges=12&d_ges=120&&ind_gest=1&&tf_ges="+tf_ges+"&&tc_ges=8&&o_ges=BUZON";
		}else if(id==2){
			document.location.href="functions/gestiones.php?idcliente="+idcliente+"&tipo=gest&cta="+cuenta+"&fges="+f_ges+"&r_ges=12&d_ges=119&&ind_gest=1&&tf_ges="+tf_ges+"&&tc_ges=12&&o_ges=COLGO";
		}else if(id==3){
			document.location.href="functions/gestiones.php?idcliente="+idcliente+"&tipo=gest&cta="+cuenta+"&fges="+f_ges+"&r_ges=13&d_ges=123&&ind_gest=1&&tf_ges="+tf_ges+"&&tc_ges=12&&o_ges=FAX";
	}
		
}
function gs_gs(){

	/*if(document.getElementById("ubicabilidad_c")){
		var  ubi_cli=document.getElementById("ubicabilidad_c").value
		if(ubi_cli==""){
			alert("Elegir un horario de ubicabilidad para el cliente!");
			return false;
		}
	}*/

	var edit_pre=0;
	if(document.getElementById("editid2")){
		var edit_pre=1;
	}
	
	idcliente=document.getElementById("idcliente").value
	id_pr=document.getElementById("id_user_p").value

	cuentas = document.getElementById('cuenta2').getElementsByTagName("option")
	var valt=cuentas.length
	if(valt=='0'){
		document.getElementById("e_gg").innerHTML="<font color='red'>No hay cuentas para gestionar</font>"
		return false;
	}
		var r_ges = document.getElementById('resultado_gs').value	
		validar=r_ges.split("-")
		if(validar[0]=="0"){
			var z=0;
			var total_cuentas=""	
				for(i=0,z=0;i<valt;i++){
						if(cuentas[i].selected){
							if(i==valt-1){
								if(z>=1){
									total_cuentas+=",";
								}
								total_cuentas+=cuentas[i].value;
								break;
							}
								if(z>=1){
									total_cuentas+=",";
								}
								total_cuentas+=cuentas[i].value;
							z++;
						}
				}
		}		
	var cuenta = document.getElementById('cuenta2').value
	var f_ges= document.getElementById('g_fg').value
	var r_ges = document.getElementById('resultado_gs').value
		if(validar[0]=="0"){
			much_cuent=total_cuentas.split(",")
			total=much_cuent.length
			if(total>1){
				var cuenta=total_cuentas
			}
		}
	
	var d_ges = document.getElementById('det_ges').value
	var f_comp = document.getElementById('desde').value
	var i_compg= document.getElementById('g_icg').value
	var ind_gest = document.getElementById('g_ig').value
		
		if(isNaN(i_compg)){ 
			alert("Ingrese un monto valido!")
			return false;
		}
		
		if(validar[1]==2 || validar[1]==248 || d_ges==321 || d_ges==322 || d_ges==323){
			if(i_compg==""){
				alert("Ingrese un monto de compromiso Por favor !")
				return false;
			}

			if( f_comp.length!=10){
				alert("Fecha de Promesa Invalida !")
				return false;
			}
		}
		
		if(validar[1]==1 || validar[1]==254 || validar[1]==351){
			if(i_compg==""){
				alert("Ingrese un monto de confirmacion Por favor !")
				return false;
			}

			if( f_comp.length!=10){
				alert("Fecha de Confirmacion Invalida !")
				return false;
			}
		}
		
		if(validar[0]=="0"){
			var i_compg=""
		}
		
		if(document.getElementById('g_tg')){
			var tf_ges = document.getElementById('g_tg').value
			if(tf_ges=="" && ind_gest==1){
				document.getElementById("e_gg").innerHTML="<font color='red'>Faltan Datos</font>"
				return false;
			}
			
		}
		
		if(ind_gest==9){
			if(document.getElementById('g_drg')){
				var dr_ges = document.getElementById('g_drg').value
					if(dr_ges==""){
						document.getElementById("e_gg").innerHTML="<font color='red'>Faltan Datos</font>"
						return false;
					}	
			}
			
			var dir_t=document.getElementsByName("gca_dt")
			total = dir_t.length
			for(var i = 0; i <= total; i++) {
						if(dir_t[i].checked){
							var dir_t=dir_t[i].value
							break
						}
			}
		}else{
			var dir_t="";
		}
	var tc_ges = document.getElementById('g_tcg').value
	var o_ges = document.getElementById('g_obsg').value
	
	if(document.getElementById("idllam").value!=""){
		o_ges = "PREDICTIVO | "+o_ges;
	}
	
	if(o_ges.indexOf("#")!=-1){
		var o_ges = o_ges.replace('#','N*');
	}
	
	if(d_ges==225){
		a=document.getElementById('ape_pa').value;
		b=document.getElementById('ape_ma').value;
		c=document.getElementById('tv_nom').value;
		d=document.getElementById('tv_fc').value;
		e=document.getElementById('tv_sx').value;
		f=document.getElementById('tv_dir').value;
		g=document.getElementById('tv_ref_dir').value;
		h=document.getElementById('tv_ds').value;
		i=document.getElementById('tv_pr').value;
		j=document.getElementById('tv_dp').value;
		
		o_ges=o_ges+"("+a+"*"+b+"*"+c+"*"+d+"*"+e+"*"+f+"*"+g+"*"+h+"*"+i+"*"+j+")";
		
	}else if(d_ges==226){
		a=document.getElementById('f_ent').value;
		b=document.getElementById('h_ent').value;
		c=document.getElementById('d_ent').value;
		d=document.getElementById('ds_ent').value;
		e=document.getElementById('p_ent').value;
		f=document.getElementById('dp_ent').value;
		g=document.getElementById('ref_dir_e').value;
		h="";
		i="";
		j="";
		
		o_ges=o_ges+"("+a+"*"+b+"*"+c+"*"+d+"*"+e+"*"+f+"*"+g+"*"+h+"*"+i+"*"+j+")";
	
	}
	
	if(document.getElementById("val_cta")){
		var val_cta_ce=document.getElementById("val_cta").value;
		if(val_cta_ce==""){
			document.getElementById("e_gg").innerHTML="<font color='red'>Faltan Datos</font>";
			return false;
		}else if(val_cta_ce==1){
			val_cta_ce="NO, MI DIRECCION ES OTRA : "+val_cta_ce;
		}else if(val_cta_ce==0){
			val_cta_ce="";
		}
		
	}else{ var val_cta_ce=""; }
	
	var obj=document.getElementById('btn_gest');
	var gst_email=document.getElementById("gst_email").value
	var ind=document.getElementById("g_ig").value
	
		if(ind!=3 & ind!=6 & ind!=9){
			var tf_v = document.getElementById('t_v').value
			if(tf_v==""){
				document.getElementById("e_gg").innerHTML="<font color='red'>Faltan Datos</font>"
				return false;
			}
			
			ajax=Ajax();
				ajax.open("GET", "functions/val_fono.php?val="+tf_v+"&idfono="+tf_ges,true);
				ajax.onreadystatechange=function() {
						if (ajax.readyState==4) {return 1;}
				}
			ajax.send(null)
		}
	
		if(ind==3 || ind==7 || ind==6){
			tf_ges="0";
		}
		
		if(ind==3 || ind==9 ){
			tc_ges=17;
		}
		
		if(ind==9){
			r_ges=12;
			d_ges=113;
		}
	
	o_ges_enc="";
	if(ind!=9){
		var rs_en=r_ges.split("-");
		if(rs_en[1]==67){
			
			if(!document.getElementById("p5")){
					p1=document.getElementById("p1").value;
					p2=document.getElementById("p2").value;
					p3=document.getElementById("p3").value;
					nr3=document.getElementById("nr_3").value;
					p4=document.getElementById("p4").value;
					
					if(p1==32){
						p2="0";
					} 
					
					if(p1=="" || p2=="" || p3=="" || p4=="" ){
						alert("Falta Preguntas por Responder");
						return false;
					}
					
					if(p3==2){
						if(nr3==""){
							alert("Por Favor ingresar una direccion");
							document.getElementById("nr_3").focus();
							return false;
						}
					} 
	
					o_ges_enc=p1+"*"+p2+"*"+p3+"*"+p4+"*"+nr3;
			}else{
				var d_encuesta="";
					
					p1=document.getElementById("p1").value;
					p2=document.getElementById("p2").value;
					p3=document.getElementById("p3").value;
					p4=document.getElementById("p4").value;
					p5=document.getElementById("p5").value;
					p6=document.getElementById("p6").value;
					p7=document.getElementById("p7").value;
					p8=document.getElementById("p8").value;
				if(p1=="" || p4=="" || p5=="" || p6=="" || p7=="" || p8==""){
					alert("Falta Preguntas por Responder");
					return false;
				}
				
				if(!document.getElementById("p9")){
					if(p1==3 || p1==4){
						if(p2==""){	alert("Falta Preguntas por Responder");			return false;	}
					}
				}else{
					if(p2==3 || p2==4){
					if(p3==""){	alert("Falta Preguntas por Responder");			return false;	}}
				}
				
				if(document.getElementById("p9")){
					p9=document.getElementById("p9").value;
					obs1_ec="";obs3_ec="";obs9_ec="";
					
					if(p9==""){
						alert("Falta Preguntas por Responder");
						return false;
					}
					if(p3 == 9)
					{
						obs3_ec=document.getElementById("otros_3").value
					}
					//------------------------------------------------------------------
					if(p1 == 8)
					{
						obs1_ec=document.getElementById("otros_1").value
					}
					//--------------------------------------------------------------------
					if(p9 == 6)
					{
						obs9_ec=document.getElementById("otros_9").value
					}
				}

				o_ges_enc=p1+"*"+p2+"*"+p3+"*"+p4+"*"+p5+"*"+p6+"*"+p7+"*"+p8;
				if(document.getElementById("p9")){
					o_ges_enc+="*"+p9+"*"+obs1_ec+"*"+obs3_ec+"*"+obs9_ec;
				}
			}
			
		}
	
	}

	if(ind>=10){d_ges="0";}
	if ( cuenta=="" || f_ges==""  || r_ges==""  || d_ges==""  || ind==""  ||   tc_ges==""  ){
		document.getElementById("e_gg").innerHTML="<font color='red'>Faltan Datos</font>";
		return false;
	}
	
	if(gst_email==""){
		gst_email=0;
	}
	
	idtareaf="";
	if(document.getElementById("id_tarea")){
		if(document.getElementById("id_tarea").value!=""){
		var idtarea=document.getElementById("id_tarea").value
		idtareaf="&t_estado=1&idtarea="+idtarea;

		}
	}
	ges_idf="";
	if(document.getElementById("id_gt")){
		if(document.getElementById("id_gt").value!=""){
		var id_ges=document.getElementById("id_gt").value
		ges_idf="&id_gest="+id_ges;

		}
	}
	
	if(document.getElementById("id_pre_ta").value!=""){
						var idpredic="&idpredi="+document.getElementById("id_pre_ta").value;
				}else{
						var idpredic="";
				}
	if(document.getElementById('USER_AUTH_TOKEN_APPLICATION')){
			if(document.getElementById('USER_AUTH_TOKEN_APPLICATION').value!=""){
				var pre_fl="&USER_AUTH_TOKEN_APPLICATION="+document.getElementById('USER_AUTH_TOKEN_APPLICATION').value;
			}
		}
	if(document.getElementById('new_t')){
		tareaf="";
		if(document.getElementById('new_t').checked==true){
			if(document.getElementById('coment_tarea').value=="" || document.getElementById('fechatarea').value=="" || document.getElementById('horatarea').value==""){
				document.getElementById("e_gg").innerHTML="<font color='red'>Introduzca una Tarea</font>"
				return false;
			}
			if(document.getElementById('coment_tarea').value!=""){
				
				var id=document.getElementById('id_cli_gs').value
				var fec_t=document.getElementById('fechatarea').value
				var hor_t=document.getElementById('horatarea').value
				var com_t=document.getElementById('coment_tarea').value
				ajax=Ajax();
				var tr_ges=r_ges.split("-")
				
				/*obj.disabled = 'true';
				obj.className="none";*/
				

				
				if(tr_ges[1]==2){
					document.location.href="functions/gestiones.php?editid2="+edit_pre+pre_fl+"&idcliente="+idcliente+"&tipo=gest&cta="+cuenta+"&fges="+f_ges+"&r_ges="+tr_ges[1]+"&d_ges="+d_ges+"&&f_comp="+f_comp+"&&i_compg="+i_compg+"&&ind_gest="+ind_gest+"&&tf_ges="+tf_ges+"&dr_ges="+dr_ges+"&&tc_ges="+tc_ges+"&&o_ges="+o_ges+"&id="+id+"&fec_t="+fec_t+"&hor_t="+hor_t+"&com_t="+com_t+"&idre="+r_ges+"&mail="+gst_email+idtareaf+ges_idf+idpredic+"&det_cta="+val_cta_ce+"&encuesta="+o_ges_enc;
				}
				tareaf="&id="+id+"&fec_t="+fec_t+"&hor_t="+hor_t+"&com_t="+com_t+"&idre="+r_ges;

			}
		}
	}
	
	
	var id=document.getElementById('id_cli_gs').value
	var sURL = unescape(window.location.pathname);
	
	if(ind!=9){
		var tr_ges=r_ges.split("-")
	}else{
		var tr_ges=r_ges
	}
	

	
	if(tr_ges[1]!=2){
		/*obj.disabled = 'true';
		obj.className="none";*/
		
		document.location.href="functions/gestiones.php?editid2="+edit_pre+"&idcliente="+idcliente+"&tipo=gest&cta="+cuenta+"&fges="+f_ges+"&r_ges="+r_ges+"&d_ges="+d_ges+"&&f_comp="+f_comp+"&&i_compg="+i_compg+"&&ind_gest="+ind_gest+"&&tf_ges="+tf_ges+"&dr_ges="+dr_ges+"&&tc_ges="+tc_ges+"&&o_ges="+o_ges+"&mail="+gst_email+"&val_dir="+dir_t+tareaf+idtareaf+ges_idf+idpredic+"&det_cta="+val_cta_ce+"&encuesta="+o_ges_enc+pre_fl;

	}
	
}

function gest_datos(value,pag){
		ajax=Ajax();
			ajax.open("GET", "functions/gest_tipo.php?pag="+pag+"&id="+value,true);
			ajax.onreadystatechange=
				function() {
					if (ajax.readyState==4) {
						document.getElementById("span_gestiones_call").innerHTML=ajax.responseText
					}
				}
			ajax.send(null)
}




	function fec_ges(){
			var divdatos=document.getElementById("e_gg")
            var valor1 = document.getElementById("g_fg").value
            var valor2 = document.getElementById("desde").value
        var rs_gs = document.getElementById("resultado_gs").value
			rs_gs= rs_gs.split("-")
			rs_gs=rs_gs[1]
			
        if(rs_gs=="1" || rs_gs=="254" || rs_gs=="351"){
			if(valor1<valor2){
				alert("La fecha no puede ser mayor a la fecha de gestion")
				document.getElementById("desde").value=""
				return 0;
			}else{
			
				return 0;
			}
        }
        
        if(valor1>valor2){
			alert("La fecha no puede ser menor a la fecha de gestion")
			document.getElementById("desde").value=""
            return 0;
        }else{
			divdatos.innerHTML="";
		}
	}
	
	function fec_tar(){
			
            var valor1 = document.getElementById("g_fg").value
            var valor2 = document.getElementById("fechatarea").value
		
		if(valor2.length>10 || valor2.length<10){
			alert("Ingrese un fecha Valida");
			document.getElementById("fechatarea").value=""
			return 0;
		}	
		
		
		if(valor1>valor2){
			alert("La fecha de tarea no puede ser anterior la fecha actual")
			document.getElementById("fechatarea").value=""
			return 0;
        }
	}

	function mostrar_provincias(datos) {
		var provincias = eval(datos);
		var obj = document.getElementById('prov');
			obj.length = 0;
			obj.options[0] = new Option('Todos', '');
				for(contador = 0; contador < provincias.length; contador++){
					obj.options[contador + 1] = new Option(provincias[contador][1], provincias[contador][0]);
				}
	}
//---------------------------------------------------------------------
	function dpto()	{
		var iddpto=document.getElementById('dpto').value
			ajax=Ajax();
			ajax.open("GET", "dpto.php?"+"&&id_dpto="+iddpto,true);
			ajax.onreadystatechange=
				function() {
					if (ajax.readyState==4) {
						datos = ajax.responseText;
						mostrar_provincias(datos);
						mostrar_distritos();
						
							
						
					}
				}
			ajax.send(null)
		if(iddpto!=''){
			document.getElementById('gs_dpto').className='zpIsValid'
		}else{
			document.getElementById('gs_dpto').className='zpNotValid'
		}
	}
//---------------------------------------------------------------------
	function dist()	{
		var iddpto=document.getElementById('dpto').value
		var idprov=document.getElementById('prov').value
			ajax=Ajax();
			ajax.open("GET", "distritos.php?"+"&&id_dpto="+iddpto+"&&id_prov="+idprov,true);
			ajax.onreadystatechange=
				function() {
					if (ajax.readyState==4) {
						datos = ajax.responseText;
						mostrar_distritos(datos);
					}
				}
			ajax.send(null)
		if(idprov!=''){
			document.getElementById('gs_prov').className='zpIsValid'
		}else{
			document.getElementById('gs_prov').className='zpNotValid'
		}
	}
//---------------------------------------------------------------------
	function mostrar_distritos(datos) {
		var distritos = eval(datos);
		var obj = document.getElementById('dist');
			obj.length = 0;
			obj.options[0] = new Option('Todos', '');
				for(contador = 0; contador < distritos.length; contador++) {
					obj.options[contador + 1] = new Option(distritos[contador][1], distritos[contador][0]);
				}
	}
//---------------------------------------------------------------------
	function cart(){
		if(document.getElementById('u_prove')){
			var idprov=document.getElementById('u_prove').value
		}
		if(document.getElementById('prov') && document.getElementById('prov').value!=""){
			var idprov=document.getElementById('prov').value
		}
			ajax=Ajax();
			ajax.open("GET", "sel_cart.php?"+"&&id_prove="+idprov,true);
			ajax.onreadystatechange=
				function() {
					if (ajax.readyState==4) {
						datos = ajax.responseText;
						if (datos==""){
							var obj = document.getElementById('u_cart');
							obj.length = 0;
							obj.options[0] = new Option('--Seleccionar--', '');
							
							var obj = document.getElementById('r_cartera');
							obj.length = 0;
							obj.options[0] = new Option('--Seleccionar--', '');
							return false;
						}
						m_cart(datos);
						var obj = document.getElementById('r_cartera');
						obj.length = 0;
						obj.options[0] = new Option('--Seleccionar--', '');
						//m_r_cart();
					}
				}
			ajax.send(null)
	}
//---------------------------------------------------------------------
	function m_cart(datos) {
		var cart = eval(datos);
		if(document.getElementById('u_cart')){
			var obj = document.getElementById('u_cart');
		}
		if(document.getElementById('id_c')){
			var obj = document.getElementById('id_c');
		}
			obj.length = 0;
			obj.options[0] = new Option('--Seleccionar--', '');
				for(contador = 0; contador < cart.length; contador++) {
					obj.options[contador + 1] = new Option(cart[contador][1], cart[contador][0]);
				}
	}
//---------------------------------------------------------------------
	function r_cart() {
		var idcart=document.getElementById('u_cart').value
			ajax=Ajax();
			ajax.open("GET", "sel_rc.php?"+"&&id_cart="+idcart,true);
			ajax.onreadystatechange=
				function() {
					if (ajax.readyState==4) {
						datos = ajax.responseText;
						m_r_cart(datos);
					}
				}
			ajax.send(null)
	}
//---------------------------------------------------------------------
	function m_r_cart(datos) {
		
		var cart = eval(datos);
		var obj = document.getElementById('r_cartera');
			obj.length = 0;
			obj.options[0] = new Option('--Seleccionar--', '');
				for(contador = 0; contador < cart.length; contador++) {
					obj.options[contador + 1] = new Option(cart[contador][1], cart[contador][0]);
				}
			tpcart();
	}
//---------------------------------------------------------------------
	function tpcart(){
		if(document.getElementById('u_cart')){
			var idcart=document.getElementById('u_cart').value
		}
		
		if(document.getElementById('cartera')){
			var idcart=document.getElementById('cartera').value
		}
			ajax=Ajax();
			ajax.open("GET", "c_tpc.php?"+"&&id_cart="+idcart,true);
			ajax.onreadystatechange=
				function() {
					if (ajax.readyState==4) {
						datos = ajax.responseText;
						show_tc(datos);
					}
					
				}
			ajax.send(null)
}


function show_tc(datos) {
		var show_tc = eval(datos);
		var obj = document.getElementById('u_tcart');
			obj.length = 0;
			obj.options[0] = new Option('Todos', '');
				for(contador = 0; contador < show_tc.length; contador++){
					obj.options[contador + 1] = new Option(show_tc[contador][1], show_tc[contador][0]);
		}
}
//----------------------------------------------
	function nw_mail() {
	
		var id = document.getElementById('id_cli_gs').value
		var mail = document.getElementById('e_email').value
		var ori = document.getElementById('e_ot').value
		var ft = 2
		var obs = document.getElementById('e_obt').value
		
		prioe=document.getElementsByName('e_pts')
		var radioLength = prioe.length;
		for(var i = 0; i < radioLength; i++) {
			if(prioe[i].checked){
				var pe = prioe[i].value;
				
			}
		}
		
		if ( ori=="" || mail==""  || ft=="" ){
			document.getElementById("e_m").innerHTML="<font color='red'>Faltan Datos</font>"
			return false;
		}
		var sURL = unescape(window.location.pathname);
		document.location.href="functions/gestiones.php?tipo=mail&id="+id+"&mail="+mail+"&&ori="+ori+"&&ft="+ft+"&&obs="+obs+"&&prio="+pe;

		
	}


