function rep_bco_c_trama_tv(ind){
	if(ind==0){
		var fecini = document.getElementById("fec_ges_i").value
	}else if(ind==1){
		var fecini = 99

	}
	var peri = document.getElementById("periodo").value


	if(fecini==""){
		alert("Elija un Fecha")
		document.getElementById("fec_ges_i").focus();
		return false;
	}

	
	if(peri==""){
		alert("Elija un Periodo")
		document.getElementById("periodo").focus();
		return false;
	}

	cart=document.getElementById('u_cart').value;
			if(cart==""){
				alert("Elija una cartera");
				return false;
			}
			
	tcart=document.getElementById('u_tcart').value;
			if(tcart==""){
				alert("Elija una campaña");
				return false;
			}

	
	web="functions/xls_trama_tv.php?peri="+peri+"&fecini="+fecini+"&idcartera="+cart+"&idtipocartera="+tcart;

	window.open(web,"mywindow","menubar=1,resizable=1,width=400,height=600");


}

function libera_cta(v){
		if(confirm("Esta seguro de liberar las ctas para este usuario?")){
		var idcart=	document.getElementById('u_cart').value
	
		if(idcart==""){alert("Elija una cartera ");return false;}
		
		//idcart=1;
		param="idusuario="+v+"&idcartera="+idcart;
		web="functions/resultados_x_ct.php?"+param;
		
		if(document.getElementById('u_tcart') && document.getElementById('gst')){
		var gst=document.getElementById('gst').value;
			var tip_c=document.getElementById('u_tcart').value;
			if(tip_c=="" ){
				alert("Elija una Campaña");
				return false;
			}

			
			web="functions/resultados_x_ind_tv.php?"+param+"&tcart="+tip_c;
		}
		
		if(document.getElementById('u_rs') && !document.getElementById('u_tcart')){
				web="functions/resultados_x_rs.php?"+param+"&idresultado="+document.getElementById('u_rs').value+"&liberar=1";
		}
		
		if(document.getElementById('u_tcart') && document.getElementById('u_rs')){
				web="functions/resultados_x_rs_tv.php?"+param+"&idresultado="+document.getElementById('u_rs').value+"&liberar=1&tcart="+document.getElementById('u_tcart').value;
		}
		
		if(document.getElementById('inc')){
			var inc=document.getElementById('inc').value;
			var cla=document.getElementById('cla').value;
			var rie=document.getElementById('rie').value;
			var desde=document.getElementById('desde_s').value;
			var hasta=document.getElementById('hasta_s').value;
			var grupo=document.getElementById('grp').value;
			var pagos=document.getElementById('pgs').value;
			if(document.getElementById('gst')){
				var gst=document.getElementById('gst').value;
			}
			if(desde!=""){
				if(hasta==""){
					alert("Elija la cantidad final del rango");
					return false;
				}
			}
			if(inc=="" && cla=="" && rie=="" && grupo=="" && pagos=="" && gst==""){
				alert("Elija por lo menos un indicador");
				return false;
			}
			
			
			web="functions/resultados_x_ind.php?"+param+"&incremental="+inc+"&clasificacion="+cla+"&riesgo="+rie+"&liberar=1&desde="+desde+"&hasta="+hasta+"&grupo="+grupo+"&pagos="+pagos+"&gestion="+gst;
		}
		
		
			
			
			
		//alert(web)
		//return false;
		divResultado = document.getElementById('rslt_uc');
		//return false;
				ajax=Ajax();
				ajax.open("GET",web,true);
				divResultado.innerHTML = "<center><img src='imag/icono-cargando.gif'/></center>";
				setTimeout("",15000);
				ajax.onreadystatechange=
					function() {
					if (ajax.readyState==4) {
						divResultado.innerHTML = ajax.responseText
					}
				}
				ajax.send(null)
				return false;	
		}
}

function rep_bco_c_trama(ind){
	if(ind==0){
		var fecini = document.getElementById("fec_ges_i").value
	}else if(ind==1){
		var fecini = 99

	}
	var peri = document.getElementById("periodo").value


	if(fecini==""){
		alert("Elija un Fecha")
		document.getElementById("fec_ges_i").focus();
		return false;
	}

	
	if(peri==""){
		alert("Elija un Periodo")
		document.getElementById("periodo").focus();
		return false;
	}

	cart=document.getElementById('u_cart').value;
			if(cart==""){
				alert("Elija una cartera");
				return false;
			}

	
	web="functions/xls_trama_cencosud.php?peri="+peri+"&fecini="+fecini+"&idcartera="+cart;

	window.open(web,"mywindow","menubar=1,resizable=1,width=400,height=600");


}

function rep_cenco_riesgos(){
	var pe = document.getElementById("periodo").value
	var cart= document.getElementById("u_cart").value
	var rsg= document.getElementById("u_riesgo").value
		if(rsg==""){
			alert("Por Favor Elija una Riesgo"); 
			document.getElementById("u_riesgo").focus();
			return false;
		}
		
		if(cart==""){
			alert("Por Favor Elija una cartera"); 
			document.getElementById("u_cart").focus();
			return false;
		}
		
		if(pe==""){
			alert("Por favor Elija un periodo"); 
			document.getElementById("periodo").focus();
			return false;
		}
			
		window.open("functions/xls_riesgos_cenco.php?riesgo="+rsg+"&cart="+cart+"&peri="+pe,"mywindow","menubar=1,resizable=1,width=400,height=600");

}



function rep_cenco_conta(){
	var pe = document.getElementById("periodo").value
	var cart= document.getElementById("u_cart").value
		if(cart==""){
			alert("Por Favor Elija una cartera"); 
			document.getElementById("u_cart").focus();
			return false;
		}
		
		if(pe==""){
			alert("Por favor Elija un periodo"); 
			document.getElementById("periodo").focus();
			return false;
		}
			
		window.open("functions/xls_contacto_cenco.php?cart="+cart+"&peri="+pe,"mywindow","menubar=1,resizable=1,width=400,height=600");

}


function res_c(id){
	divResultado = document.getElementById('rslt_uc');
		var idcart=	document.getElementById('u_cart').value
		
		web="functions/resultados_x_ct.php?idcartera="+idcart;

		if(id==5){ web="functions/resultados_x_rs_tv.php?idcartera="+idcart+"&idresultado="+document.getElementById('u_rs').value+"&tcart="+document.getElementById('u_tcart').value;}
		if(id==2){ web="functions/resultados_x_rs.php?idcartera="+idcart+"&idresultado="+document.getElementById('u_rs').value;}
		if(id==3){ 
			var inc=document.getElementById('inc').value;
			var cla=document.getElementById('cla').value;
			var rie=document.getElementById('rie').value;
			var gst=document.getElementById('gst').value;
			var desde=document.getElementById('desde_s').value;
			var hasta=document.getElementById('hasta_s').value;
			var grupo=document.getElementById('grp').value;
			var pagos=document.getElementById('pgs').value;
			if(desde!=""){
				if(hasta==""){
					alert("Elija la cantidad final del rango");
					return false;
				}
			}
			
			if(inc=="" && cla=="" && rie=="" && gst=="" && grupo=="" && pagos==""){
				alert("Elija por lo menos un indicador");
				return false;
			}
			
			
			web="functions/resultados_x_ind.php?desde="+desde+"&hasta="+hasta+"&idcartera="+idcart+"&incremental="+inc+"&clasificacion="+cla+"&riesgo="+rie+"&gestion="+gst+"&grupo="+grupo+"&pagos="+pagos;
		}
		
		if(id==4){ 
			
			var gst=document.getElementById('gst').value;
			var tip_c=document.getElementById('u_tcart').value;
			if(tip_c=="" ){
				alert("Elija una Campaña");
				return false;
			}
			
			
			web="functions/resultados_x_ind_tv.php?idcartera="+idcart+"&gestion="+gst+"&tcart="+tip_c;
		}
			ajax=Ajax();
			ajax.open("GET",web,true);
			divResultado.innerHTML = "<center><img src='imag/icono-cargando.gif'/></center>";
			setTimeout("",15000);
			ajax.onreadystatechange=
				function() {
				if (ajax.readyState==4) {
					divResultado.innerHTML = ajax.responseText

				}
				
			}
			ajax.send(null)
			return false;
}

function up_res_c(vl){
		var usr=document.getElementsByName('res_c')
		/*if(document.getElementById('c_id')){
			var idcamp=document.getElementById('c_id').value
		}*/
		var usuarios="";
		var tot_u="";
	if(vl==1){
		if(usr[0].checked){
			for(i=1;i<usr.length;i++){
				usr[i].checked=true;
			}
		}else if(!usr[0].checked){
			for(i=1;i<usr.length;i++){
				usr[i].checked=false;
			}
		
		}
		return false;
	}
	
	if(vl==0){
		
		for(i=1;i<usr.length;i++){
			if(usr[i].checked){
				usuarios+=usr[i].value+",";
				if(document.getElementById("inc")){
					if(document.getElementById('u_'+usr[i].value).value!=""){
						tot_u+=document.getElementById('u_'+usr[i].value).value+",";
					}
				}
				
				if(document.getElementById("u_tcart") && !document.getElementById("u_rs")){
					if(document.getElementById('gst')){
					if(document.getElementById('u_'+usr[i].value).value!=""){
						tot_u+=document.getElementById('u_'+usr[i].value).value+",";
					}
					}
				}
			}
		}
		
		var idcart=	document.getElementById('u_cart').value
		//var idagente=document.getElementById('usu_cart').value
		
		if(idcart==""){alert("Elija una cartera ");return false;}
		if(usuarios==""){alert("Elija al menos un usuario");return false;}
		
		
		//idcart=1;
		param="idusuarios="+usuarios+"&idcartera="+idcart+"&tot_u="+tot_u;
		var web="functions/resultados_x_ct.php?"+param;
		if(document.getElementById("u_rs")){
			var idresultado=	document.getElementById('u_rs').value
			if(idresultado==""){alert("Elija una resultado ");return false;}
			if(document.getElementById("u_tcart")){
				var web="functions/resultados_x_rs_tv.php?idresultado="+idresultado+"&"+param+"&tcart="+document.getElementById("u_tcart").value;
			}else{
				var web="functions/resultados_x_rs.php?idresultado="+idresultado+"&"+param;
			}
		}
		
		if(document.getElementById("inc")){
			var inc=document.getElementById('inc').value;
			var cla=document.getElementById('cla').value;
			var rie=document.getElementById('rie').value;
			
			var desde=document.getElementById('desde_s').value;
			var hasta=document.getElementById('hasta_s').value;
			var grupo=document.getElementById('grp').value;
			var pagos=document.getElementById('pgs').value;
			if(desde!=""){
				if(hasta==""){
					alert("Elija la cantidad final del rango");
					return false;
				}
			}
			
			if(inc=="" && cla=="" && rie=="" && grupo=="" && pagos==""){
				alert("Elija por lo menos un indicador");
				return false;
			}
			
			
			var web="functions/resultados_x_ind.php?desde="+desde+"&hasta="+hasta+"&incremental="+inc+"&clasificacion="+cla+"&riesgo="+rie+"&"+param+"&grupo="+grupo+"&pagos="+pagos;
		}
		
		if(document.getElementById("u_tcart") && !document.getElementById("u_rs")){
			var gst=document.getElementById('gst').value;
			var tip_c=document.getElementById('u_tcart').value;
			if(tip_c=="" ){
				alert("Elija una Campaña");
				return false;
			}
			
			
			var web="functions/resultados_x_ind_tv.php?idcartera="+idcart+"&gestion="+gst+"&tcart="+tip_c+"&"+param;		
			
		}
		//alert(web);
		


		divResultado = document.getElementById('rslt_uc');
		
				ajax=Ajax();
				ajax.open("GET",web,true);
				divResultado.innerHTML = "<center><img src='imag/icono-cargando.gif'/></center>";
				ajax.onreadystatechange=
					function() {
					if (ajax.readyState==4) {
						divResultado.innerHTML = ajax.responseText
						return false;
					}
				}
				ajax.send(null)
				return false;	
	}
	
	if(vl==2){
		var idcart=	document.getElementById('u_cart').value
		var idagente=document.getElementById('usu_cart').value
		var hora=document.getElementById('hora_r').value
		
		if(idcart==""){alert("Elija una cartera ");return false;}
		if(idagente==""){alert("Elija un agente ");return false;}
	
		
		//idcart=1;
		param="ctas=1&idcartera="+idcart+"&idagente="+idagente;
		web="functions/resultados_x_ct.php?"+param;

			divResultado = document.getElementById('rslt_uc');
		
				ajax=Ajax();
				ajax.open("GET",web,true);
				divResultado.innerHTML = "<center><img src='imag/icono-cargando.gif'/></center>";
				setTimeout("",15000);
				ajax.onreadystatechange=
					function() {
					if (ajax.readyState==4) {
						divResultado.innerHTML = ajax.responseText
					}
				}
				ajax.send(null)
				return false;	
	}
	
	
	
}


function rep_bco_c(){
	var tip_r = document.getElementById("reporte_bco_c").value
	var fecini = document.getElementById("fec_ges_i").value
	var peri = document.getElementById("periodo").value
	
	/*var tipo_c = document.getElementById("tipo_cartera").value*/
	/*var fecfin = document.getElementById("fec_ges_f").value*/
	
	if(tip_r=="200"){web="functions/txt_cencosud_ges.php?fec="+fecini;}
	if(tip_r=="600"){web="functions/txt_cencosud_pagos.php?fec="+fecini;}
	if(tip_r=="700"){web="functions/txt_cencosud_fono.php?fec="+fecini;}
	if(tip_r=="800"){web="functions/txt_cencosud_dir.php?fec="+fecini;}

	
	if(tip_r==""){
		alert("Elija un Reporte")
		document.getElementById("reporte_bco_c").focus();
		return false;
	}

	if(fecini==""){
		alert("Elija un Fecha")
		document.getElementById("fec_ges_i").focus();
		return false;
	}

	
	if(peri==""){
		alert("Elija un Periodo")
		document.getElementById("periodo").focus();
		return false;
	}
	/*
	
	
	if(tipo_c=="" && tip_r!="ics_tn"){
		alert("Elija un Tipo de Cartera")
		document.getElementById("tipo_c").focus();
		return false;
	}*/
	web+="&peri="+peri;

	divResultado = document.getElementById('resultado_bco_c');
		
			ajax=Ajax();
			ajax.open("GET",web,true);
			divResultado.innerHTML = "<center><img src='imag/icono-cargando.gif'/></center>";
			ajax.onreadystatechange=
				function() {
				if (ajax.readyState==4) {
					divResultado.innerHTML = ajax.responseText
				}
				
			}
			ajax.send(null)
			return false;



}

function rep_xxx(){
	var peri = document.getElementById("periodo").value
	var prove = document.getElementById("u_prove").value
	var cart = document.getElementById("u_cart").value
	var fecini= document.getElementById("fec_ges_i").value
	var idusuario= document.getElementById("usu_cart").value
	
	if(prove==""){
		alert("Elija un Proveedor")
		document.getElementById("u_prove").focus();
		return false;
	}
	
	if(cart==""){
		alert("Elija un Cartera")
		document.getElementById("u_cart").focus();
		return false;
	}
	
	if(fecini==""){
		alert("Elija un Fecha")
		document.getElementById("fec_ges_i").focus();
		return false;
	}
	
	
	web="prove="+prove;
	
	if(cart!=""){web+="&cart="+cart;}
	if(peri!=""){web+="&peri="+peri;}
	if(fecini!=""){web+="&fecini="+fecini;}
	if(idusuario!=""){web+="&idusuario="+idusuario;}
	
	window.open('functions/xls_reporte_diario_gg.php?'+web, 'ventana_busqueda', 'height=500,width=400,status=yes,toolbar=no,scrollbars=yes,menubar=no,location=no')
}

function rep_cob(){
	var peri = document.getElementById("periodo").value
	var prove = document.getElementById("u_prove").value
	var cart = document.getElementById("u_cart").value
	if(prove==""){
		alert("Elija un Proveedor")
		document.getElementById("u_prove").focus();
		return false;
	}
	
	if(cart==""){
		alert("Elija un Cartera")
		document.getElementById("u_cart").focus();
		return false;
	}
	
	web="prove="+prove;
	
	if(cart!=""){web+="&cart="+cart;}
	if(peri!=""){web+="&peri="+peri;}

	window.open('functions/xls_reporte_cobertura.php?'+web, 'ventana_busqueda', 'height=500,width=400,status=yes,toolbar=no,scrollbars=yes,menubar=no,location=no')
}



function updC(id){
	var tip_t=document.getElementById("tipotel_c"+id).value
	var p1=document.getElementById("p1"+id).value
	var p2=document.getElementById("p2"+id).value
	
	$.ajax({
			url :'functions/f_campana.php?acc=up&tt='+tip_t+"&idC="+id+"&p1="+p1+"&p2="+p2,
			success : function(data){
				showOp(id,0);

			}
		
	});
	
}	

function insC(){
	var nm=document.getElementById("n_campana").value
	
	$.ajax({
			url :'functions/f_campana.php?acc=ins&nm='+nm,
			success : function(data){
				showNewC('s_campana',0);
				$('#content_tk').fadeOut(300, function() {  });
				$('#content_tk').fadeIn(300, function() 
					{ 
						$('#content_tk').load('includes/campana.php?rl=1');
						document.getElementById("n_campana").value="";
					}
				);
				
				//$('#content_tk').load('functions/insert_tk.php');
				//Shadowbox.close();
			}
		
	});
	
}

function showNewC(id,flag){
				if(flag==1){v="visible";p="relative";}else{v="hidden";p="absolute";}
				  $('#'+id).fadeOut(300, function() {		
					$('#'+id).css('visibility', v);
					$('#'+id).css('position', p);
				  });
				 $('#'+id).fadeIn(300, function() {  });
			
	}
function showOp(id,flag){
				if(flag==1){v="visible";p="relative";}else{v="hidden";p="absolute";}	
				  $('#'+id).fadeOut(300, function() {		
					$('#'+id).css('visibility', v);
					$('#'+id).css('position', p);
				  });
				 $('#'+id).fadeIn(300, function() {  });
	}


function chTk(a,b){
	idTk=b;
	dt=a;
	
	$.ajax({
			url :'functions/insert_tk.php?acc=edit&det='+dt+'&idTk='+idTk,
			success : function(data){
				$('#content_tk').load('functions/insert_tk.php');
				Shadowbox.close();
			}
		
	});
	
}

function setTicket(id){
	
    // open a welcome message as soon as the window loads
    Shadowbox.open({
        content:    'includes/ticket_det.php?id='+id,
		player :    'iframe',
        title:      "Ingresar Detalle",
        height:     360,
        width:      400
    });


}

function showTicket(){
				  $('#ing_tick').fadeOut(300, function() {		
					$('#ing_tick').css('visibility', 'visible');
					$('#ing_tick').css('position', 'relative');
				  });
				 $('#ing_tick').fadeIn(300, function() {  });
			
	}
	
function addTicket(){
	t=document.getElementById("tipo_tk").value;
	dt=document.getElementById("det_tk").value;
	$.ajax({
			url :'functions/insert_tk.php?idT='+t+"&dt="+dt,
			success : function(data){
				$('#content_tk').html(data);
				$('#ing_tick').fadeOut(300);
				document.getElementById("det_tk").value="";
				document.getElementById("tipo_tk").value="";
			}
		
	});
}





function rep_tn(tipo){
	var peri = document.getElementById("periodo").value
	var fecini = document.getElementById("fec_ges_i").value
	var fecfin = document.getElementById("fec_ges_f").value
	var cartera = document.getElementById("cartera").value
	var tcart=document.getElementById("u_tcart").value
	var web="";
	if(peri==""){
		alert("Elija un Periodo")
		document.getElementById("periodo").focus();
		return false;
	}
	
	if(fecini==""){
		alert("Elija un x lo menos la fecha de inicio")
		document.getElementById("fec_ges_i").focus();
		return false;
	}
	web+="peri="+peri;
	if(cartera==""){
		alert("Elija una Cartera")
		document.getElementById("cartera").focus();
		return false;
	}
	web+="&cartera="+cartera;
	if(document.getElementById("fec_ra").checked){
		if(fecini!="" & fecfin==""){
			web+="&fecini="+fecini+"&fecfin="+fecini;
		}
		if(fecini!="" & fecfin!=""){
			web+="&fecini="+fecini+"&fecfin="+fecfin;
		}
	}
	
	if(tcart!=""){
		web+="&tcart="+tcart;
	}
	
	if(tipo=="resume"){
		ventana = window.open("functions/reporte_fc.php?"+web, "ventana_busqueda", "height=500,width=400,status=yes,toolbar=no,scrollbars=yes,menubar=no,location=no");
	}else if(tipo=="trama"){
		ventana = window.open("functions/xlsx_r_tn_tv.php?"+web, "ventana_busqueda", "height=500,width=400,status=yes,toolbar=no,scrollbars=yes,menubar=no,location=no");
	}
	
	//var divResultado = document.getElementById('show_report_r');
	//if(cart!=""){web+="&cart="+cart;}
	//if(ind!=""){web+="&actv="+ind;}
	//if(fecfin!="" & fecini==""){alert("Elegir una Fecha de Inicio");return false;}
	
	
}

function rpp_ll(id){
	var peri = document.getElementById("periodo").value
	var prove = document.getElementById("u_prove").value
	var cart = document.getElementById("u_cart").value
	
	if(peri==""){
		alert("Elija un Periodo")
		document.getElementById("periodo").focus();
		return false;
	}
	
	if(prove==""){
		alert("Elija un Proveedor")
		document.getElementById("u_prove").focus();
		return false;
	}
	
	if(cart==""){
		alert("Elija una Cartera")
		document.getElementById("u_cart").focus();
		return false;
	}

	web="prove="+prove;
	
	if(cart!=""){web+="&cart="+cart;}
	if(peri!=""){web+="&peri="+peri;}

	if(id=="M"){ 
		webF="functions/xls_rpp_llamadas_show.php?";
		divResultado = document.getElementById('rpta_ll_rpp');
		
			ajax=Ajax();
			ajax.open("GET",webF+web,true);
			divResultado.innerHTML = "<center><img src='imag/icono-cargando.gif'/></center>";
			ajax.onreadystatechange=
				function() {
				if (ajax.readyState==4) {
					divResultado.innerHTML = ajax.responseText
				}
				
			}
			ajax.send(null)
			return false;
	}else{
		window.open('functions/xls_rpp_llamadas.php?'+web, 'ventana_busqueda', 'height=500,width=400,status=yes,toolbar=no,scrollbars=yes,menubar=no,location=no')
	}
}

function rep_rpp_a(){
	var peri = document.getElementById("periodo").value
	var prov=document.getElementById("u_prove").value
	var cart=document.getElementById("u_cart").value
	
	if(prov==""){
		alert("Elija una Proveedor")
		document.getElementById("u_prove").focus();
		return false;
	}
	
	if(cart==""){
		alert("Elija una Cartera")
		document.getElementById("u_cart").focus();
		return false;
	}
	
	if(peri==""){
		alert("Elija una Periodo")
		document.getElementById("periodo").focus();
		return false;
	}
	
	web="xls_anticuamiento.php?";


	web+="periodo="+peri;
	web+="&cartera="+cart;
	
	/*if(document.getElementById("fec_ra").checked){
		if(fecini!="" & fecfin==""){web+="&fecini="+fecini+"&fecfin="+fecini;}
		if(fecini!="" & fecfin!=""){web+="&fecini="+fecini+"&fecfin="+fecfin;}
	}*/
	
	//if(fecfin!="" & fecini==""){alert("Elegir una Fecha de Inicio");return false;}
	window.open('functions/'+web, 'ventana_busqueda', 'height=500,width=400,status=yes,toolbar=no,scrollbars=yes,menubar=no,location=no');
	
}

function contactos_rpp(){
	idcli= document.getElementById('id_cli_gs').value
	ventana = window.open("functions/contactos_rpp.php?idcliente="+idcli, "ventana_busqueda", "height=400,width=500,status=yes,toolbar=no,scrollbars=yes,menubar=no,location=no");
}

function us_x_ct(flag,usucar){
		if(flag==0){
			divResultado = document.getElementById('usuarios_carteras');
			var pro=document.getElementById('u_prove2').value
			var cart=document.getElementById('u_cart2').value
			if(pro==""){alert("Elija Proveedor por favor."); return false;}
			if(cart==""){alert("Elija una Cartera por favor."); return false;}
			
			if (confirm("Esta seguro de asignar esta cartera al usuario seleccionado?")) {
					var us=document.getElementById('uxc').value

					url="functions/users_x_carteras_ch.php?idusuario="+us+"&idpro="+pro+"&idcart="+cart;
					ajax=Ajax();
						ajax.open("GET",url ,true);

						ajax.onreadystatechange=
							function() {
								if (ajax.readyState==4) {
									divResultado.innerHTML = ajax.responseText	
								}
							}
					ajax.send(null)
						//document.getElementById(id).value='1';
						//document.getElementById(prid).value='0';
				}else {

				}
		}else{
			if(flag==1){ var msg="Desactivar"; estado=0;}
			if(flag==2){ var msg="Activar";estado=1;}
			if (confirm("Esta seguro de "+msg+" este usuario?")) {
					//var us=document.getElementById('uxc_c').value
					var user=document.getElementById('uxc').value
					url="functions/users_x_carteras_ch.php?idestado="+estado+"&iduc="+usucar+"&idusuario="+user;
					ajax=Ajax();
						ajax.open("GET",url ,true);

						ajax.onreadystatechange=
							function() {
								if (ajax.readyState==4) {
									divResultado.innerHTML = ajax.responseText	
								}
							}
					ajax.send(null)
						//document.getElementById(id).value='1';
						//document.getElementById(prid).value='0';
				}else {

				}
		}
}


function users_c_c2(val){
	divResultado = document.getElementById('usuarios_carteras');
	if(val!=""){
	
		web="functions/users_x_carteras_ch.php?idusuario="+val;
		
	}else{
	 return false;
	}
			ajax=Ajax();
			ajax.open("GET",web,true);
			divResultado.innerHTML = "<center><img src='imag/icono-cargando.gif'/></center>";
			setTimeout("",15000);
			ajax.onreadystatechange=
				function() {
				if (ajax.readyState==4) {
					divResultado.innerHTML = ajax.responseText

				}
				
			}
			ajax.send(null)
			return false;
}

function users_c_c(val){
	divResultado = document.getElementById('rslt_uc');
	if(val!="s_u"){
		var idcart=	document.getElementById('u_cart').value
		web="functions/users_x_carteras.php?idcartera="+idcart;
	}
			ajax=Ajax();
			ajax.open("GET",web,true);
			divResultado.innerHTML = "<center><img src='imag/icono-cargando.gif'/></center>";
			setTimeout("",15000);
			ajax.onreadystatechange=
				function() {
				if (ajax.readyState==4) {
					divResultado.innerHTML = ajax.responseText

				}
				
			}
			ajax.send(null)
			return false;
}


function up_email_c(vl){
		var usr=document.getElementsByName('email_ck')
		var ctas="";
		var cart=document.getElementById("cart").value
		var tv;
		
	if(cart==""){ alert("Elije una Cartera Por Favor."); return false;}
	if(vl==1){
		if(usr[0].checked){
			for(i=1;i<usr.length;i++){
				usr[i].checked=true;
			}
		}else if(!usr[0].checked){
			for(i=1;i<usr.length;i++){
				usr[i].checked=false;
			}
		
		}
		return false;
	}
	
	
	for(i=1;i<usr.length;i++){
		if(usr[i].checked){
			ctas+=usr[i].value+",";
		}
	}
	//alert(ctas);
	
	if(document.getElementById('ven1').checked){tv=document.getElementById('ven1').value}
	if(document.getElementById('ven2').checked){tv=document.getElementById('ven2').value}
	if(document.getElementById('ven3').checked){tv=document.getElementById('ven3').value}
	
	
	param="ctas="+ctas+"&t="+tv+"&cartera="+cart;
	web="functions/e_mail.php?"+param;
		
	divResultado = document.getElementById('rpta_mail');
	
			ajax=Ajax();
			ajax.open("GET",web,true);
			divResultado.innerHTML = "<center><img src='img/enviando2.gif'/></center>";
			setTimeout("",15000);
			ajax.onreadystatechange=
				function() {
				if (ajax.readyState==4) {
					divResultado.innerHTML = ajax.responseText
				}
			}
			ajax.send(null)
			return false;


}


function rpta_ec1_2(){
	var cartera=+document.getElementById("cartera").value
	
	if(cartera==""){alert("Elija una Cartera");return false;}
	
	if(cartera==37){
		window.open('functions/reporte_e2.php?cartera='+cartera,'nuevaVentana','width=300, height=400')
	}else{
		window.open('functions/reporte_e.php?cartera='+cartera,'nuevaVentana','width=300, height=400')
	}
	
}

function rep_claro(tipo){
	var peri = document.getElementById("periodo").value
	var fecini = document.getElementById("fec_ges_i").value
	var fecfin = document.getElementById("fec_ges_f").value
	var cartera = document.getElementById("cartera").value
	
	var web="";
	if(peri==""){
		alert("Elija un Periodo")
		document.getElementById("periodo").focus();
		return false;
	}
	
	if(fecini==""){
		alert("Elija un x lo menos la fecha de inicio")
		document.getElementById("fec_ges_i").focus();
		return false;
	}
	web+="peri="+peri;
	if(cartera==""){
		alert("Elija una Cartera")
		document.getElementById("cartera").focus();
		return false;
	}
	web+="&cartera="+cartera;
	if(document.getElementById("fec_ra").checked){
		if(fecini!="" & fecfin==""){
			web+="&fecini="+fecini+"&fecfin="+fecini;
		}
		if(fecini!="" & fecfin!=""){
			web+="&fecini="+fecini+"&fecfin="+fecfin;
		}
	}
	
	
	if(tipo=="resume"){
		ventana = window.open("functions/reporte_fc.php?"+web, "ventana_busqueda", "height=500,width=400,status=yes,toolbar=no,scrollbars=yes,menubar=no,location=no");
	}else if(tipo=="trama"){
		ventana = window.open("functions/xlsx_r_claro_tv.php?"+web, "ventana_busqueda", "height=500,width=400,status=yes,toolbar=no,scrollbars=yes,menubar=no,location=no");
	}
	
	//var divResultado = document.getElementById('show_report_r');
	//if(cart!=""){web+="&cart="+cart;}
	//if(ind!=""){web+="&actv="+ind;}
	//if(fecfin!="" & fecini==""){alert("Elegir una Fecha de Inicio");return false;}
	
	
}


function c_rpp() {
		var imp=document.getElementById("importar").value

		var archivo=document.getElementById("seleccion").value
		if(!document.tipo_impor.tip_imp && imp=="Importar Archivo"){
			var archivo=document.getElementById("seleccion").value
			var tr_rpp=document.getElementById("tr_rpp").value
			
			if(tr_rpp==""){
				alert("Por favor Elija el tipo de archivo a convertir");
				return false;
			}
			
			alert("Ahora se importara el archivo");
		
			divResultado = document.getElementById('resultado');
		
			ajax=Ajax();
			
			if(tr_rpp=="AP"){
				ajax.open("GET","importar/script_imp/conversor_rpp.php?archivo="+archivo,true);
			}else if(tr_rpp=="AE"){
				ajax.open("GET","importar/script_imp/conversor_rpp_entrega.php?archivo="+archivo,true);
			}else if(tr_rpp=="CT"){
				ajax.open("GET","importar/script_imp/i_ex_rpp.php?archivo="+archivo,true);
			
			}
			divResultado.innerHTML = "<center><img src='imag/icono-cargando.gif'/></center>";
			setTimeout("",15000);
			ajax.onreadystatechange=
				function() {
				if (ajax.readyState==4) {
					divResultado.innerHTML = ajax.responseText
					document.getElementById("importar").value='Seleccionar Archivo'
					document.getElementById("seleccion").value=''
				}
				
			}
			ajax.send(null)
			return false;
		}	
				
		ventana = window.open("importar/importar.php", "ventana_busqueda", "height=500,width=400,status=yes,toolbar=no,scrollbars=yes,menubar=no,location=no");
	
	}

function up_users_c(vl){
		var usr=document.getElementsByName('user_c')
		if(document.getElementById('c_id')){
			var idcamp=document.getElementById('c_id').value
		}
		var usuarios="";
	
	if(vl==1){
		if(usr[0].checked){
			for(i=1;i<usr.length;i++){
				usr[i].checked=true;
			}
		}else if(!usr[0].checked){
			for(i=1;i<usr.length;i++){
				usr[i].checked=false;
			}
		
		}
		return false;
	}
	
	
	for(i=1;i<usr.length;i++){
		if(usr[i].checked){
			usuarios+=usr[i].value+",";
		}
	}
	idcart=1;
	param="&idusuario="+usuarios+"&idcampana="+idcamp;
	if(vl==2){
		web="functions/users_x_ct_feed.php?idcartera="+idcart+param;
	}else{
		var idcart=	document.getElementById('u_cart').value
		web="functions/users_x_ct.php?idcartera="+idcart+param;
	}
	
	
	//alert(param);
	divResultado = document.getElementById('rslt_uc');
	
			ajax=Ajax();
			ajax.open("GET",web,true);
			divResultado.innerHTML = "<center><img src='imag/icono-cargando.gif'/></center>";
			setTimeout("",15000);
			ajax.onreadystatechange=
				function() {
				if (ajax.readyState==4) {
					divResultado.innerHTML = ajax.responseText
				}
			}
			ajax.send(null)
			return false;


}

function users_c(val){
	divResultado = document.getElementById('rslt_uc');
	if(val!="s_u"){
		var idcart=	document.getElementById('u_cart').value
		web="functions/users_x_ct.php?idcartera="+idcart;
	}else{
		var id_c=document.getElementById('c_id').value
		web="functions/users_x_ct_feed.php?idcampana="+id_c;
	}
			ajax=Ajax();
			ajax.open("GET",web,true);
			divResultado.innerHTML = "<center><img src='imag/icono-cargando.gif'/></center>";
			setTimeout("",15000);
			ajax.onreadystatechange=
				function() {
				if (ajax.readyState==4) {
					divResultado.innerHTML = ajax.responseText

				}
				
			}
			ajax.send(null)
			return false;
}


function i_gcm() {
		var imp=document.getElementById("importar").value

		var archivo=document.getElementById("seleccion").value
		if(!document.tipo_impor.tip_imp && imp=="Importar Archivo"){
			var archivo=document.getElementById("seleccion").value
			var peri=document.getElementById("periodo").value
			if(peri==""){
				alert("Selecciona un Periodo");
				document.getElementById("periodo").focus();
				return false;
			}
			
			idp="&periodo="+peri;
			alert("Ahora se importara el archivo");
		
			divResultado = document.getElementById('resultado');
		
			ajax=Ajax();
			ajax.open("GET","importar/script_imp/subir_gestiones_camp.php?archivo="+archivo+idp,true);
			divResultado.innerHTML = "<center><img src='imag/icono-cargando.gif'/></center>";
			setTimeout("",15000);
			ajax.onreadystatechange=
				function() {
				if (ajax.readyState==4) {
					divResultado.innerHTML = ajax.responseText
					document.getElementById("importar").value='Seleccionar Archivo'
					document.getElementById("seleccion").value=''
				}
				
			}
			ajax.send(null)
			return false;
		}	
				
		ventana = window.open("importar/importar.php", "ventana_busqueda", "height=500,width=400,status=yes,toolbar=no,scrollbars=yes,menubar=no,location=no");
	
}

function i_gc() {
		var imp=document.getElementById("importar").value

		var archivo=document.getElementById("seleccion").value
		if(!document.tipo_impor.tip_imp && imp=="Importar Archivo"){
			var archivo=document.getElementById("seleccion").value
			var peri=document.getElementById("periodo").value
			if(peri==""){
				alert("Selecciona un Periodo");
				document.getElementById("periodo").focus();
				return false;
			}
			
			idp="&periodo="+peri;
			alert("Ahora se importara el archivo");
		
			divResultado = document.getElementById('resultado');
		
			ajax=Ajax();
			ajax.open("GET","importar/script_imp/subir_gestiones_call.php?archivo="+archivo+idp,true);
			divResultado.innerHTML = "<center><img src='imag/icono-cargando.gif'/></center>";
			setTimeout("",15000);
			ajax.onreadystatechange=
				function() {
				if (ajax.readyState==4) {
					divResultado.innerHTML = ajax.responseText
					document.getElementById("importar").value='Seleccionar Archivo'
					document.getElementById("seleccion").value=''
				}
				
			}
			ajax.send(null)
			return false;
		}	
				
		ventana = window.open("importar/importar.php", "ventana_busqueda", "height=500,width=400,status=yes,toolbar=no,scrollbars=yes,menubar=no,location=no");
	
}


function rep_TN(){
	var tip_r = document.getElementById("reporte_tn").value
	var peri = document.getElementById("periodo").value
	var fecini = document.getElementById("fec_ges_i").value
	var tipo_c = document.getElementById("tipo_cartera").value
	/*var fecfin = document.getElementById("fec_ges_f").value*/
	
	if(tip_r=="ics_tn"){
		web="functions/ics_tn.php?";
	}else{
		web="functions/xlsx_gest_tn.php?";
	}
	
	if(tip_r==""){
		alert("Elija un Reporte")
		document.getElementById("reporte_tn").focus();
		return false;
	}
	
	if(peri==""){
		alert("Elija un Periodo")
		document.getElementById("periodo").focus();
		return false;
	}
	
	if(fecini==""){
		alert("Elija un Fecha")
		document.getElementById("fecini").focus();
		return false;
	}
	
	if(tipo_c=="" && tip_r!="ics_tn"){
		alert("Elija un Tipo de Cartera")
		document.getElementById("tipo_c").focus();
		return false;
	}

	
	web+="peri="+peri+"&fecini="+fecini+"&t_cartera="+tipo_c;

	divResultado = document.getElementById('resultado_r_c');
		
			ajax=Ajax();
			ajax.open("GET",web,true);
			divResultado.innerHTML = "<center><img src='imag/icono-cargando.gif'/></center>";
			ajax.onreadystatechange=
				function() {
				if (ajax.readyState==4) {
					divResultado.innerHTML = ajax.responseText
				}
				
			}
			ajax.send(null)
			return false;

	/*if(document.getElementById("fec_ra").checked){
		if(fecini!="" & fecfin==""){web+="&fecini="+fecini+"&fecfin="+fecini;}
		if(fecini!="" & fecfin!=""){web+="&fecini="+fecini+"&fecfin="+fecfin;}
	}*/


}

function r_camp() {
		var id_c=document.getElementById("c_id").value
		var t_rep=document.getElementById("tipo_dato").value
		
		if(t_rep=="L"){var web="functions/xlsx_del_campana.php";}
		if(t_rep=="TG"){var web="functions/xlsx_trans_data.php";}
		if(t_rep=="SC"){var web="functions/xlsx_up_data.php";}
		if(t_rep=="RG"){var web="functions/xlsx_rep_ges_pre.php";}
		if(t_rep=="AM"){var web="functions/xlsx_data_a.php";}
			
			divResultado = document.getElementById('resultado_r_c');
		
		if(t_rep=="L"){
			if(confirm("Esta seguro de borrar los datos de esta base?")){
				if(confirm("Realmente esta seguro de borrar los datos de esta base?")){
				
				}else{
					return false;
				}
			}else{
				return false;
			}
		
		}	
			ajax=Ajax();
			ajax.open("GET",web+"?campana="+id_c,true);
			divResultado.innerHTML = "<center><img src='imag/icono-cargando.gif'/></center>";
			ajax.onreadystatechange=
				function() {
				if (ajax.readyState==4) {
					divResultado.innerHTML = ajax.responseText
				}
				
			}
			ajax.send(null)
			return false;

}


function datos_fd(){
	var tipd = document.getElementById("tipo_dato").value
	var peri = document.getElementById("periodo").value
	var prove = document.getElementById("u_prove").value
	var cart = document.getElementById("u_cart").value

	if(tipd==""){
		alert("Elija un Dato")
		document.getElementById("tipo_dato").focus();
		return false;
	}
	
	if(prove==""){
		alert("Elija un Proveedor")
		document.getElementById("u_prove").focus();
		return false;
	}
	
	if(cart==""){
		alert("Elija un Proveedor")
		document.getElementById("u_cart").focus();
		return false;
	}
	
	if(peri==""){
		alert("Elija un Periodo")
		document.getElementById("periodo").focus();
		return false;
	}

	web="prove="+prove;
	
	if(tipd!=""){web+="&tipo="+tipd;}
	if(cart!=""){web+="&cart="+cart;}
	if(peri!=""){web+="&peri="+peri;}
	
	if(tipd=="D"){var dat="dir";}
	if(tipd=="T"){var dat="tel";}
	window.open('functions/xlsx_'+dat+'.php?'+web, 'ventana_busqueda', 'height=500,width=400,status=yes,toolbar=no,scrollbars=yes,menubar=no,location=no')
	
}

function i_cl() {
		var imp=document.getElementById("importar").value

		var archivo=document.getElementById("seleccion").value
		if(!document.tipo_impor.tip_imp && imp=="Importar Archivo"){
			var archivo=document.getElementById("seleccion").value
			
			alert("Ahora se importara el archivo");
		
			divResultado = document.getElementById('resultado');
		
			ajax=Ajax();
			ajax.open("GET","importar/script_imp/del_info.php?archivo="+archivo,true);
			divResultado.innerHTML = "<center><img src='imag/icono-cargando.gif'/></center>";
			setTimeout("",15000);
			ajax.onreadystatechange=
				function() {
				if (ajax.readyState==4) {
					divResultado.innerHTML = ajax.responseText
					document.getElementById("importar").value='Seleccionar Archivo'
					document.getElementById("seleccion").value=''
				}
				
			}
			ajax.send(null)
			return false;
		}	
				
		ventana = window.open("importar/importar.php", "ventana_busqueda", "height=500,width=400,status=yes,toolbar=no,scrollbars=yes,menubar=no,location=no");
	
	}

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

function rep_tn_tv(){
	var web="";
	var peri = document.getElementById("periodo").value
	/*var fecini = document.getElementById("fec_ges_i").value
	var fecfin = document.getElementById("fec_ges_f").value*/
	var t_c=document.getElementById("u_tcart").value
	var repor_tv=document.getElementById("u_tv_rep").value
	
	if(t_c==""){
		alert("Elija un tipo de Cartera");
		return false;
	}
	
	if(repor_tv=="mg"){
		web="xlsx_fo_cart_tv_tn.php?";
	}else if(repor_tv=="ug"){
		web="xlsx_fo_cart_diario_tv_tn.php?";
	}
	if(peri==""){
		alert("Elija un Periodo")
		document.getElementById("periodo").focus();
		return false;
	}

	
	web+="peri="+peri;
	web+="&tipo_c="+t_c;
	
	if(repor_tv==""){
		alert("Elija un Reporte por favor.");
		document.getElementById("u_tv_rep").focus();
		return false;
	}
	/*if(document.getElementById("fec_ra").checked){
		if(fecini!="" & fecfin==""){web+="&fecini="+fecini+"&fecfin="+fecini;}
		if(fecini!="" & fecfin!=""){web+="&fecini="+fecini+"&fecfin="+fecfin;}
	}*/
	
	//if(cart!=""){web+="&cart="+cart;}
	
		window.open('functions/'+web, 'ventana_busqueda', 'height=500,width=400,status=yes,toolbar=no,scrollbars=yes,menubar=no,location=no')

	
}



function rep_cenco_tv(){
	var web="";
	var peri = document.getElementById("periodo").value
	/*var fecini = document.getElementById("fec_ges_i").value
	var fecfin = document.getElementById("fec_ges_f").value*/
	var t_c=document.getElementById("u_tcart").value
	var repor_tv=document.getElementById("u_tv_rep").value
	
	if(t_c==""){
		alert("Elija un tipo de Cartera");
		return false;
	}
	
	if(t_c==36){
		if(repor_tv=="mg"){
			web="xlsx_fo_cart_tv_trama_df.php?";
		}else if(repor_tv=="ug"){
			web="xlsx_fo_cart_diario_tv_trama_df.php?";
		}
		
		
	}else{
	
		if(repor_tv=="mg"){
			web="xlsx_fo_cart_tv.php?";
		}else if(repor_tv=="ug"){
			web="xlsx_fo_cart_diario_tv.php?";
		}
	}
	
	if(peri==""){
		alert("Elija un Periodo")
		document.getElementById("periodo").focus();
		return false;
	}

	
	web+="peri="+peri;
	web+="&tipo_c="+t_c;
	
	if(repor_tv==""){
		alert("Elija un Reporte por favor.");
		document.getElementById("u_tv_rep").focus();
		return false;
	}
	/*if(document.getElementById("fec_ra").checked){
		if(fecini!="" & fecfin==""){web+="&fecini="+fecini+"&fecfin="+fecini;}
		if(fecini!="" & fecfin!=""){web+="&fecini="+fecini+"&fecfin="+fecfin;}
	}*/
	
	//if(cart!=""){web+="&cart="+cart;}
		window.open('functions/'+web, 'ventana_busqueda', 'height=500,width=400,status=yes,toolbar=no,scrollbars=yes,menubar=no,location=no')
	
}


function rep_ripley(){
	var peri = document.getElementById("periodo").value
	var fecini = document.getElementById("fec_ges_i").value
	var fecfin = document.getElementById("fec_ges_f").value
	var rep=document.getElementById("reporte_r").value
	var cart=document.getElementById("reporte_r_c").value
	var tcart=document.getElementById("tipoc_r").value
	
	if(cart==""){
		alert("Elija una Cartera")
		document.getElementById("reporte_r_c").focus();
		return false;
	}
	
	
	if(rep=="gs" && cart==6){	web="ics_ripley_front.php?";}
	if(rep=="gs" && cart==2){	web="ics_ripley_prej.php?";}
	if(rep=="dt"){ web="detalle_ripley_front.php?";}
	if(rep=="ps"){ web="reporte_fc_ripley_f.php?";}
	/*if(rep=="cd"){	
		web="xlsx_r_cenco_xdia.php?";
		var has=document.getElementById("fec_ges_f")
		has.disabled="true"
		fecfin=fecini;

	}*/

	
	if(peri==""){
		alert("Elija un Periodo")
		document.getElementById("periodo").focus();
		return false;
	}
	
	web+="peri="+peri;

	if(document.getElementById("fec_ra").checked){
		if(fecini!="" & fecfin==""){web+="&fecini="+fecini+"&fecfin="+fecini;}
		if(fecini!="" & fecfin!=""){web+="&fecini="+fecini+"&fecfin="+fecfin;}
	}
	
	web+="&tipo_c="+tcart;
	var divResultado = document.getElementById('show_report_r');
	if(cart!=""){web+="&cart="+cart;}
	//if(ind!=""){web+="&actv="+ind;}
	//if(fecfin!="" & fecini==""){alert("Elegir una Fecha de Inicio");return false;}
	if(rep=="ps"){window.open('functions/'+web, 'ventana_busqueda', 'height=500,width=400,status=yes,toolbar=no,scrollbars=yes,menubar=no,location=no');}
	else{
	ajax=Ajax();
			ajax.open("GET",'functions/'+web,true);
			divResultado.innerHTML = "<center><img src='imag/cargando.gif'/></center>"

	ajax.onreadystatechange=
				function() {
				divResultado.innerHTML = "<center><img src='imag/cargando.gif'/></center>"
				if (ajax.readyState==4) {
					divResultado.innerHTML = ajax.responseText
				}
	}
	ajax.send(null)
	}
}


function ver_gestiones(capa, capa1)
	{	
		document.getElementById("span_gestiones_"+capa).style.visibility = "hidden";
		document.getElementById("span_gestiones_"+capa).style.position = "absolute";
		document.getElementById("span_gestiones_"+capa1).style.visibility = "visible";
		document.getElementById("span_gestiones_"+capa1).style.position = "relative";
	}

function rep_digit(){
	var peri = document.getElementById("periodo").value
	var prove = document.getElementById("u_prove").value
	var cart = document.getElementById("u_cart").value
	//var ind = document.getElementById("indicador").value
	var fecini = document.getElementById("fec_ges_i").value
	var fecfin = document.getElementById("fec_ges_f").value
	
	if(peri==""){
		alert("Elija un Periodo")
		document.getElementById("periodo").focus();
		return false;
	}
	if(prove==""){
		alert("Elija un Proveedor")
		document.getElementById("u_prove").focus();
		return false;
	}
	
	web="peri="+peri+"&prove="+prove;
	
	if(document.getElementById("fec_ra").checked){
		if(fecini!="" & fecfin==""){web+="&fecini="+fecini+"&fecfin="+fecini;}
		if(fecini!="" & fecfin!=""){web+="&fecini="+fecini+"&fecfin="+fecfin;}
	}
	
	if(cart!=""){web+="&cart="+cart;}
	//if(ind!=""){web+="&actv="+ind;}
	//if(fecfin!="" & fecini==""){alert("Elegir una Fecha de Inicio");return false;}
	
	window.open('functions/xlsx_r_digi.php?'+web, 'ventana_busqueda', 'height=500,width=400,status=yes,toolbar=no,scrollbars=yes,menubar=no,location=no')
}


function verif_cenco(){
	var rep=document.getElementById("reporte_c").value
	var has=document.getElementById("fec_ges_f")
	if(rep=="cd" || rep=="1g"){	
		has.disabled="true"
	}else{
		if(document.getElementById("fec_ra").checked){
		has.disabled=""
		}
	}
}

function rep_cenco(){
	var peri = document.getElementById("periodo").value
	var fecini = document.getElementById("fec_ges_i").value
	var fecfin = document.getElementById("fec_ges_f").value
	var rep=document.getElementById("reporte_c").value
	
	if(rep=="dp"){	web="xlsx_r_cenco_dp.php?";}
	if(rep=="rp"){	web="xlsx_r_cenco_rpc.php?";}
	if(rep=="tr"){	web="xlsx_r_cenco_tr.php?";}
	if(rep=="cd"){	
		web="xlsx_r_cenco_xdia.php?";
		var has=document.getElementById("fec_ges_f")
		has.disabled="true"
		fecfin=fecini;

	}
	
	if(rep=="1g"){	
		web="xlsx_r_cenco_1er_gh.php?";
		var has=document.getElementById("fec_ges_f")
		has.disabled="true"
		fecfin=fecini;

	}
	if(rep=="ch"){	web="xlsx_r_cenco_xhora.php?";}
	
	if(peri==""){
		alert("Elija un Periodo")
		document.getElementById("periodo").focus();
		return false;
	}

	
	web+="peri="+peri;

	
	if(document.getElementById("fec_ra").checked){
		if(fecini!="" & fecfin==""){web+="&fecini="+fecini+"&fecfin="+fecini;}
		if(fecini!="" & fecfin!=""){web+="&fecini="+fecini+"&fecfin="+fecfin;}
	}
	
	//if(cart!=""){web+="&cart="+cart;}
	//if(ind!=""){web+="&actv="+ind;}
	//if(fecfin!="" & fecini==""){alert("Elegir una Fecha de Inicio");return false;}
	
	window.open('functions/'+web, 'ventana_busqueda', 'height=500,width=400,status=yes,toolbar=no,scrollbars=yes,menubar=no,location=no')
}

function rep_seg(){
	var peri = document.getElementById("periodo").value
	var prove = document.getElementById("u_prove").value
	var cart = document.getElementById("u_cart").value
	//var ind = document.getElementById("indicador").value
	var fecini = document.getElementById("fec_ges_i").value
	var fecfin = document.getElementById("fec_ges_f").value
	
	if(peri==""){
		alert("Elija un Periodo")
		document.getElementById("periodo").focus();
		return false;
	}
	if(prove==""){
		alert("Elija un Proveedor")
		document.getElementById("u_prove").focus();
		return false;
	}
	
	web="peri="+peri+"&prove="+prove;

	
	if(document.getElementById("fec_ra").checked){
		if(fecini!="" & fecfin==""){web+="&fecini="+fecini+"&fecfin="+fecini;}
		if(fecini!="" & fecfin!=""){web+="&fecini="+fecini+"&fecfin="+fecfin;}
	}
	
	if(cart!=""){web+="&cart="+cart;}
	//if(ind!=""){web+="&actv="+ind;}
	//if(fecfin!="" & fecini==""){alert("Elegir una Fecha de Inicio");return false;}
	
	window.open('functions/xlsx_r_seg.php?'+web, 'ventana_busqueda', 'height=500,width=400,status=yes,toolbar=no,scrollbars=yes,menubar=no,location=no')
}

function rep_pro(id){
	//var peri = document.getElementById("periodo").value
	var fi = document.getElementById("u_fn").value
	var fn = document.getElementById("u_fi").value
	
	if(fn< fi){
		alert("La Fecha Final no puede ser menor ala Fecha Inicio") 
		return false;
	}
	if(fi=="" || fn=="")
	{
		alert("Ingrese la fecha") 
		return false;
	}
	
	web="fi="+fi+"&fn="+fn;
	
	var prove = document.getElementById("u_prove").value
	var cart = document.getElementById("u_cart").value
	if(prove==""){
		alert("Elija un Proveedor")
		document.getElementById("u_prove").focus();
		return false;
	}
	
	if(cart==""){
		alert("Elija una Cartera")
		document.getElementById("u_prove").focus();
		return false;
	}

	web+="&prove="+prove;
	
	if(cart!=""){web+="&cart="+cart;}
	
	if(id=="E"){ 
		webF="functions/xlsx_r_pro.php?";
		window.open(webF+web, 'ventana_busqueda', 'height=500,width=400,status=yes,toolbar=no,scrollbars=yes,menubar=no,location=no')
	}
	
	if(id=="M"){ 
		webF="functions/xlsx_r_pro_show.php?";
		divResultado = document.getElementById('rpta_repor_pro');
		
			ajax=Ajax();
			ajax.open("GET",webF+web,true);
			divResultado.innerHTML = "<center><img src='imag/icono-cargando.gif'/></center>";
			ajax.onreadystatechange=
				function() {
				if (ajax.readyState==4) {
					divResultado.innerHTML = ajax.responseText
				}
				
			}
			ajax.send(null)
			return false;
	}
	
	
}


function foto_c(){
	var peri = document.getElementById("periodo").value
	var prove = document.getElementById("u_prove").value
	var cart = document.getElementById("u_cart").value
	var tpcart = document.getElementById("u_tcart").value
	if(prove==""){
		alert("Elija un Proveedor")
		document.getElementById("u_prove").focus();
		return false;
	}

	web="prove="+prove;
	
	if(cart!=""){web+="&cart="+cart;}
	if(peri!=""){web+="&peri="+peri;}
	if(tpcart!=""){web+="&tp_cart="+tpcart;}
	
	window.open('functions/xlsx_fo_cart.php?'+web, 'ventana_busqueda', 'height=500,width=400,status=yes,toolbar=no,scrollbars=yes,menubar=no,location=no')
}

function foto_gest(){
	var peri = document.getElementById("periodo").value
	var prove = document.getElementById("u_prove").value
	var cart = document.getElementById("u_cart").value
	var tpcart = document.getElementById("u_tcart").value
	//var ind = document.getElementById("indicador").value
	var fecini = document.getElementById("fec_ges_i").value
	var fecfin = document.getElementById("fec_ges_f").value
	
	if(peri==""){
		alert("Elija un Periodo")
		document.getElementById("periodo").focus();
		return false;
	}
	if(prove==""){
		alert("Elija un Proveedor")
		document.getElementById("u_prove").focus();
		return false;
	}
	
	web="peri="+peri+"&prove="+prove;
	var ind = document.getElementsByName("indicador")
	total=ind.length;
	var x=0
	
	for(i = 0; i < total; i++){
			if(ind[i].checked){
				x++
				if(x==1){web+="&actv="+ind[i].value;}else{web+="-"+ind[i].value;}
			}
	}
	
	if(document.getElementById("fec_ra").checked){
		if(fecini!="" & fecfin==""){web+="&fecini="+fecini+"&fecfin="+fecini;}
		if(fecini!="" & fecfin!=""){web+="&fecini="+fecini+"&fecfin="+fecfin;}
	}
	
	if(cart!=""){web+="&cart="+cart;}
	if(tpcart!=""){web+="&tp_cart="+tpcart;}
	//if(ind!=""){web+="&actv="+ind;}
	//if(fecfin!="" & fecini==""){alert("Elegir una Fecha de Inicio");return false;}
	
	window.open('functions/xlsx_fo_gest.php?'+web, 'ventana_busqueda', 'height=500,width=400,status=yes,toolbar=no,scrollbars=yes,menubar=no,location=no')
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

function users(){
	if(document.getElementById("u_niv").value==1 || document.getElementById("u_niv").value==""){
		var prv = document.getElementById("u_prove").getElementsByTagName("option")
		var total = prv.length
		
			for(i=0;i<total;i++){
				
				if(prv[i].value==1){
					prv[i].selected=true;
						document.getElementById("u_prove").disabled=true
						document.getElementById("t_prov").style.visibility="hidden"
						document.getElementById("t_prov").style.display="none"
						var cart=document.getElementById("u_cart").getElementsByTagName("option")
						var total2 = cart.length
						
							for(z=0;z<total2;z++){
								//*if(cart[z].value==1 ){
									
									//cart[z].selected=true;
									document.getElementById("u_cart").disabled=true;
									document.getElementById("t_cart").style.visibility="hidden"
									document.getElementById("t_cart").style.display="none"
								//}
							}
				}
			}
			
		
	}else{
		var prv = document.getElementById("u_prove").getElementsByTagName("option")
		var total = prv.length
			for(i=0;i<total;i++){
				
				if(prv[i].value==""){
					prv[i].selected=true;
						var cart=document.getElementById("u_cart").getElementsByTagName("option")
						var total2 = cart.length
						
							for(z=0;z<total2;z++){
							
								if(cart[z].value==""){
									
									cart[z].selected=true;
									document.getElementById("u_prove").disabled=false
									document.getElementById("t_prov").style.visibility="visible"
									document.getElementById("t_prov").style.display=""
									document.getElementById("u_cart").disabled=false
									document.getElementById("t_cart").style.visibility="visible"
									document.getElementById("t_cart").style.display=""
								}
							}
					
						
				}
				
				
			}
		
	}

}

function zonif_fil(){
	var iden =document.getElementById("iden").value
	var dpto =document.getElementById("dpto").value
	var prov =document.getElementById("prov").value
	var dist =document.getElementById("dist").value
	location.href='index.php?tipo=plano&parametros='+iden+'&&pag=1&&coddpto='+dpto+'&codprov='+prov+'&coddist='+dist

}
function buscar(web){
		
		if(document.getElementById('ykBody')){
			var divResultado = document.getElementById('ykBody');
		}
		
		
		if (web.indexOf('functions/consulta.php?id=&pag')!=-1) {
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
		if(web.indexOf('includes/gest_report.php')==-1 & web.indexOf('includes/fil_report.php')==-1){
			if(document.getElementById('u_prove') ){
					var prov=document.getElementById('u_prove').value
				if(prov!="" ){
					var web=web+"&prov="+prov
				}
			}

			if(document.getElementById('u_cart')){
					var cart=document.getElementById('u_cart').value
				if(cart!=""){
					var web=web+"&cart="+cart
				}
			}
		}
		if(document.getElementById('user_o')){
                var user_o=document.getElementById('user_o').value
            if(user_o!=""){
                var web=web+"&estado="+user_o
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
			
	
	
	function i_archivo() {
		var imp=document.getElementById("importar").value

		var archivo=document.getElementById("seleccion").value
		if(imp=="Importar Archivo" && document.getElementById("tipo_imp")){
			var tipo_i=document.getElementById("tipo_imp").value
			cart=document.getElementById('u_cart').value;
			if(cart==""){
				alert("Elija una cartera");
				return false;
			}

			if(tipo_i==0){
				alert("Elija el tipo de datos importacion");
				return false;
			}
			alert("Ahora se importara el archivo");
			
			if(tipo_i==1){web="importar/script_imp/leer_xls_gt.php?archivo="+archivo+"&idcartera="+cart;}
			if(tipo_i==2){web="importar/script_imp/leer_xls_cta_pagos.php?archivo="+archivo+"&idcartera="+cart;}
			//if(tipo_i==3){web="importar/script_imp/c_asignacion.php?archivo="+archivo+"&idcartera="+cart;}
			if(tipo_i==3){web="importar/script_imp/leer_xls_detalle_pagos.php?archivo="+archivo+"&idcartera="+cart;}
			if(tipo_i==4){web="importar/script_imp/convertir_data_cencosud.php?archivo="+archivo+"&idcartera="+cart;}
			if(tipo_i==5){web="importar/script_imp/leer_xls_gt_up.php?archivo="+archivo+"&idcartera="+cart;}
			if(tipo_i==6){web="importar/script_imp/leer_xls_mora.php?archivo="+archivo+"&idcartera="+cart;}
			if(tipo_i==7){web="importar/script_imp/leer_xls_tr_asig.php?archivo="+archivo+"&idcartera="+cart;}
			if(tipo_i==8){web="importar/script_imp/leer_xls_campo.php?archivo="+archivo+"&idcartera="+cart;}
			if(tipo_i==9){web="importar/script_imp/leer_xls_rs_e_tc.php?archivo="+archivo+"&idcartera="+cart;}
			if(tipo_i==10){web="importar/script_imp/leer_xls_feed_tc.php?archivo="+archivo+"&idcartera="+cart;}


			divResultado = document.getElementById('resultado');
		
			ajax=Ajax();
			ajax.open("GET",web,true);
			divResultado.innerHTML = "<center><img src='imag/icono-cargando.gif'/></center>";
			setTimeout("",15000);
			ajax.onreadystatechange=
				function() {
				if (ajax.readyState==4) {
					divResultado.innerHTML = ajax.responseText
					document.getElementById("importar").value='Seleccionar Archivo'
					document.getElementById("seleccion").value=''
				}
				
			}
			ajax.send(null)
		
		return false;
		}else if(!document.tipo_impor.tip_imp && imp=="Importar Archivo"){
			var archivo=document.getElementById("seleccion").value
			
			alert("Ahora se importara el archivo");
		
			divResultado = document.getElementById('resultado');
		
			ajax=Ajax();
			ajax.open("GET","importar/script_imp/subir_camp.php?t_imp="+tip_im+"&archivo="+archivo,true);
			divResultado.innerHTML = "<center><img src='imag/icono-cargando.gif'/></center>";
			setTimeout("",15000);
			ajax.onreadystatechange=
				function() {
				if (ajax.readyState==4) {
					divResultado.innerHTML = ajax.responseText
					document.getElementById("importar").value='Seleccionar Archivo'
					document.getElementById("seleccion").value=''
				}
				
			}
			ajax.send(null)
			return false;
		}	
		
		/*if(imp=="Importar Archivo" && document.tipo_impor.tip_imp){
			flag=0;
			var to_t=document.tipo_impor.tip_imp
			total = to_t.length;
			for(var i = 0; i < total; i++) {
				
					if(to_t[i].checked){
						var tip_im=to_t[i].value
						flag=1;
						break
					}
			}
			
			if(flag==0){
				alert("Elija el tipo de datos importacion");
				return false;
			}
			alert("Ahora se importara el archivo");
		
			divResultado = document.getElementById('resultado');
		
			ajax=Ajax();
			ajax.open("GET","importar/script_imp/ex_gest.php?t_imp="+tip_im+"&archivo="+archivo,true);
			divResultado.innerHTML = "<center><img src='imag/icono-cargando.gif'/></center>";
			setTimeout("",15000);
			ajax.onreadystatechange=
				function() {
				if (ajax.readyState==4) {
					divResultado.innerHTML = ajax.responseText
					document.getElementById("importar").value='Seleccionar Archivo'
					document.getElementById("seleccion").value=''
				}
				
			}
			ajax.send(null)
		
		return false;
		}else if(!document.tipo_impor.tip_imp && imp=="Importar Archivo"){
			var archivo=document.getElementById("seleccion").value
			
			alert("Ahora se importara el archivo");
		
			divResultado = document.getElementById('resultado');
		
			ajax=Ajax();
			ajax.open("GET","importar/script_imp/subir_camp.php?t_imp="+tip_im+"&archivo="+archivo,true);
			divResultado.innerHTML = "<center><img src='imag/icono-cargando.gif'/></center>";
			setTimeout("",15000);
			ajax.onreadystatechange=
				function() {
				if (ajax.readyState==4) {
					divResultado.innerHTML = ajax.responseText
					document.getElementById("importar").value='Seleccionar Archivo'
					document.getElementById("seleccion").value=''
				}
				
			}
			ajax.send(null)
			return false;
		}	
		*/		
		ventana = window.open("importar/importar.php", "ventana_busqueda", "height=500,width=400,status=yes,toolbar=no,scrollbars=yes,menubar=no,location=no");
	
	}
	
	function Ajax(){var xmlhttp=false;
				try {xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
				} catch (e)
				{try {
						xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
					} catch (E) {
						xmlhttp = false;
					}
				}
				if (!xmlhttp && typeof XMLHttpRequest!='undefined')
				{
					xmlhttp = new XMLHttpRequest();
				}return xmlhttp;
	}
//---------------------------------------------------------------------		
	function up_load(datos){
			var filename = document.upload.archivo.submit;
			divResultado = document.getElementById('ykBody');
			
			/*if(document.getElementById('ykBodys'))
			divResultado = document.getElementById('ykBodys');*/
			
			ajax=Ajax();
			ajax.open("POST", datos,true);

			ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");//Cabecera obligatoria para method POST

			var para="archivo="+filename+"&&action=upload";
			ajax.send(para);//enviamos parametros

			ajax.onreadystatechange=

				function() {
					if (ajax.readyState==4) {
					divResultado.innerHTML = ajax.responseText
					
					}
				}
	}
			
	
//---------------------------------------------------------------------	
	function ver_planilla(){
			document.getElementById('planillas_frame').style.position='relative';
			document.getElementById('planillas_frame').style.visibility='visible';
			document.getElementById('ykBody').innerHTML = "";
	}

	function ver_planilla_norcob(){
			document.getElementById('planillas_frame_norcob').style.position='relative';
			document.getElementById('planillas_frame_norcob').style.visibility='visible';
			document.getElementById('ykBody').innerHTML = "";
	}
	
	function mostrar(web){
		if(document.getElementById('planillas_frame')){
			document.getElementById('planillas_frame').style.position='absolute';
			document.getElementById('planillas_frame').style.visibility='hidden';
			
		}
		if(document.getElementById('planillas_frame_norcob')){
			document.getElementById('planillas_frame_norcob').style.position='absolute';
			document.getElementById('planillas_frame_norcob').style.visibility='hidden';			
		}
		divResultado = document.getElementById('ykBody');
			ajax=Ajax();
			if(web=="r_usuarios"){
				ajax.open("GET", "r_usuarios.php",true);
			}else{
				ajax.open("GET", "user.php"+"?"+web,true);
			}
			ajax.onreadystatechange=
				function() {
					if (ajax.readyState==4) {
						divResultado.innerHTML = ajax.responseText
					}
				}
			ajax.send(null)
	}
	
	//---------------------------------------------------------------------	
	function resetear(){
			id=document.getElementById("r_usuario").value
			if(id==""){return false;}
			ajax=Ajax();
			ajax.open("GET", "functions/reset.php"+"?id="+id,true);
			ajax.onreadystatechange=
				function() {
					if (ajax.readyState==4) {
					location.reload(true);
					}
				}
			ajax.send(null)
	}
//---------------------------------------------------------------------

	function validar(val){
		var dni = document.getElementById("u_ndoc").value;
		var login = document.getElementById("u_user").value;

		var obj= document.getElementById('a_user')
		divdatos = document.getElementById('ykBodys');
       	 ajax=Ajax();
         ajax.open("POST", "functions/validar.php",true);
         ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");//Cabecera obligatoria para method POST
         var datos="parametros="+val+"&valuex="+dni+"&valuey="+login;
         ajax.send(datos);          //enviamos parametros
         ajax.onreadystatechange=
            function() {
                if (ajax.readyState==4) {
                    divdatos.innerHTML = ajax.responseText
			//obj.style.display = 'none';
		      if(ajax.responseText=="Este Documento ya se encuentra registrado" || ajax.responseText=="Este nombre de usuario ya se encuentra registrado"){
			//obj.style.display = 'none';
			obj.disabled = 'true';
			obj.className="none";
			return false;
		      	 }else{
			obj.disabled = '';
			obj.className="btn";
			 }
                    //setInterval("m2('nivel')",1000);
                   //clearInterval(setInterval("m2('nivel')",1000));
                }
            }
	}
//---------------------------------------------------------------------	
function fechas(){
	var idp=document.getElementById("periodo").value
	if(idp==""){
		alert("Seleccione un Periodo")
		return false;
	}
	ajax=Ajax();
			ajax.open("GET", "includes/fechas_periodo.php"+"?idperiodo="+idp,true);
			ajax.onreadystatechange=
				function() {
					if (ajax.readyState==4) {
						var fechas=ajax.responseText.split(",")
						tot=fechas.length
						var des=document.getElementById("fec_ges_i")
						des.length = 0;
						des.options[0] = new Option('..Seleccione', '');
							for(i = 0; i < tot; i++){
								var dat=fechas[i].split("/")
								des.options[i+1] = new Option(dat[0], dat[1]);
							}
						var has=document.getElementById("fec_ges_f")
						has.length = 0;
						has.options[0] = new Option('..Seleccione', '');
							for(i = 0; i < tot; i++){
								var dat=fechas[i].split("/")
								has.options[i+1] = new Option(dat[0], dat[1]);
							}
					}	
				}
	ajax.send(null)
}
//---------------------------------------------------------------------	
function sel_fecha(){
	var des=document.getElementById("fec_ges_i")
	var has=document.getElementById("fec_ges_f")
	
	if(document.getElementById("reporte_c")){
		var rep=document.getElementById("reporte_c").value
	}
	if(document.getElementById("fec_ra").checked){
		des.disabled=""
		if(document.getElementById("reporte_c")){
			if(rep!="cd"){
				has.disabled=""
			}
		}else{
			has.disabled=""
		}
		
	}else{
		des.disabled="true"
		has.disabled="true"
	}
}

//---------------------------------------------------------------------	
	function m2(web){
		divResultado = document.getElementById('ykBody');
			ajax=Ajax();
			ajax.open("GET", "param.php"+"?parametros="+web,true);
			ajax.onreadystatechange=
				function() {
					if (ajax.readyState==4) {
						$("#ykBody").hide()
						$("#ykBody").show();
						divResultado.innerHTML = ajax.responseText
					}
				}
			ajax.send(null)
	}
//---------------------------------------------------------------------	
	function m3(web){
		divResultado = document.getElementById('ykBody');
			ajax=Ajax();
			ajax.open("GET", "proveedor.php"+"?parametros="+web,true);
			ajax.onreadystatechange=
				function() {
					if (ajax.readyState==4) {
						$("#ykBody").show();
						divResultado.innerHTML = ajax.responseText
					}
				}
			ajax.send(null)
	}
//---------------------------------------------------------------------	
	function m4(web){
		divResultado = document.getElementById('ykBody');
			ajax=Ajax();
			ajax.open("GET", "periodos.php"+"?parametros="+web,true);
			ajax.onreadystatechange=
				function() {
					if (ajax.readyState==4) {
						$("#ykBody").show();
						divResultado.innerHTML = ajax.responseText
					}
				}
			ajax.send(null)
	}
//---------------------------------------------------------------------	
	function m5(web){
		divResultado = document.getElementById('ykBody');
			ajax=Ajax();
			ajax.open("GET", "gestiones.php"+"?parametros="+web,true);
			ajax.onreadystatechange=
				function() {
					if (ajax.readyState==4) {
						$("#ykBody").show();
						if(ajax.responseText=="Dato Registrado Correctamente"){
						
						}
						divResultado.innerHTML = ajax.responseText
					}
				}
			ajax.send(null)
	}
//---------------------------------------------------------------------	
	function m6(web,pag,id){
		divResultado = document.getElementById('ykBody');
			if(!pag){pag="";}
			if(!id){id="";}
				ajax=Ajax();
				ajax.open("GET", "zonificacion.php"+"?parametros="+web+"&&pag="+pag+"&&id_dpto="+id,true);
				divResultado.innerHTML="Cargando..."
				ajax.onreadystatechange=
					function() {
						if (ajax.readyState==4) {
							$("#ykBody").show();
							divResultado.innerHTML = ajax.responseText
						}
					}
				ajax.send(null)
	}
//---------------------------------------------------------------------	
	function m7(tipo,pag){
		divResultado = document.getElementById('ykBody');
		var id1= document.getElementById('dpto').value
		var id2= document.getElementById('prov').value
		var id3= document.getElementById('dist').value
			ajax=Ajax();
			ajax.open("GET", "zonificacion_filt.php"+"?tipo="+tipo+"&&pag="+pag+"&&id_dpto="+id1+"&&id_prov="+id2+"&&id_dist="+id3,true);
			divResultado.innerHTML="Cargando..."
			ajax.onreadystatechange=
				function(){
					if (ajax.readyState==4) {
						$("#ykBody").show();
						divResultado.innerHTML = ajax.responseText
					}
				}
			ajax.send(null)
	}
//---------------------------------------------------------------------	
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
						obj = document.getElementById('dist');
						obj.length = 0;
						obj.options[0] = new Option('Todos', '');
						
							
						
					}
				}
			ajax.send(null)
		/*if(iddpto!=''){
			document.getElementById('gs_dpto').className='zpIsValid'
		}else{
			document.getElementById('gs_dpto').className='zpNotValid'
		}*/
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
		/*if(idprov!=''){
			document.getElementById('gs_prov').className='zpIsValid'
		}else{
			document.getElementById('gs_prov').className='zpNotValid'
		}*/
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
	function usuarios_cart(){
		
		var idcart=document.getElementById("u_cart").value;
		ajax=Ajax();
			ajax.open("GET", "sel_usu_cart.php?"+"idcart="+idcart,true);
			ajax.onreadystatechange=
				function() {
					if (ajax.readyState==4) {
						datos = ajax.responseText;
						if (datos==""){
							var obj = document.getElementById('usu_cart');
							obj.length = 0;
							obj.options[0] = new Option('--Seleccionar--', '');
							

							return false;
						}else{
							usu_cart_repo(datos);
						}

					}
				}
			ajax.send(null)
	
	}
//---------------------------------------------------------------------
	function usu_cart_repo(datos) {
		var ucart = eval(datos);
		var obj = document.getElementById('usu_cart');
		
			obj.length = 0;
			obj.options[0] = new Option('--Seleccionar--', '');
				for(contador = 0; contador < ucart.length; contador++) {
					obj.options[contador + 1] = new Option(ucart[contador][1], ucart[contador][0]);
				}
	}
//---------------------------------------------------------------------
	function cart(id){
		if(document.getElementById('u_prove')){
			var idprov=document.getElementById('u_prove').value
		}
		if(document.getElementById('prov') && document.getElementById('prov').value!=""){
			var idprov=document.getElementById('prov').value
		}
		
		if(document.getElementById('u_prove') && document.getElementById('u_prove').value!=""){
			var idprov=document.getElementById('u_prove').value
		}
		if(id==2){
			var idprov=document.getElementById('u_prove2').value
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
						if(id==2){m_cart2(datos);}else{
							m_cart(datos);
						}
						if(document.getElementById('r_cartera')){
						var obj = document.getElementById('r_cartera');
						obj.length = 0;
						obj.options[0] = new Option('--Seleccionar--', '');
						}
						
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
	
	function m_cart2(datos) {
		var cart = eval(datos);
		if(document.getElementById('u_cart2')){
			var obj = document.getElementById('u_cart2');
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
						if(document.getElementById('r_cartera')){
						m_r_cart(datos);
						}
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
	}
//---------------------------------------------------------------------
	 function insert(web){
		if(document.getElementById("tipo_cart")){
			var valor = document.getElementById("tipo_cart").value;
			if(document.getElementById("tipc_estado")){
				var valor2 = document.getElementById("tipc_estado").value;
			}
		}
		
		if(document.getElementById("fuente")){
			var valor = document.getElementById("fuente").value;
			if(document.getElementById("ft_estado")){
				var valor2 = document.getElementById("ft_estado").value;
			}
		}
		 if(document.getElementById("nivel")){
			var valor = document.getElementById("nivel").value;
			if(document.getElementById("n_estado")){
				var valor2 = document.getElementById("n_estado").value;
			}
		}
 
		if(document.getElementById("moneda")){
			var valor = document.getElementById("moneda").value;
			if(document.getElementById("mn_estado")){
				var valor2 = document.getElementById("mn_estado").value;   
			}
			var valor3 = document.getElementById("s_moneda").value;
			if(valor3==""){
				valor3="-"
			}
		}
 
		if(document.getElementById("parent")){
			var valor = document.getElementById("parent").value;
			if(document.getElementById("pr_estado")){
				var valor2 = document.getElementById("pr_estado").value;
			}
		}

		if(document.getElementById("pers")){
			var valor = document.getElementById("pers").value;
			if(document.getElementById("p_estado")){
				var valor2 = document.getElementById("p_estado").value;
			}
		}

		if(document.getElementById("doc")){
			var valor = document.getElementById("doc").value;
			if(document.getElementById("d_estado")){
				var valor2 = document.getElementById("d_estado").value;
			}
		}

		divdatos = document.getElementById('ykBodys');

        
			if(document.getElementById("peri")){
				var valor = document.getElementById("peri").value;
				if(document.getElementById("desde")){var valor2 = document.getElementById("desde").value;}
				if(document.getElementById("hasta")){var valor3 = document.getElementById("hasta").value;}
				if(valor2>=valor3){
				divdatos.innerHTML="La fecha de inicio no puede ser mayor o igual a de la fecha final";
				return 0;
				}
			}
		

        
           
		if(document.getElementById("u_name")){
			var valor = document.getElementById("u_name").value;
			var valor2 = document.getElementById("u_doc").value
			var valor3 = document.getElementById("u_ndoc").value;
			if(web.indexOf("update")!=-1){
			
			}else{
				if(valor3!=""){
					 var obj= document.getElementById('a_user')
					 ajax=Ajax();
					 ajax.open("POST", "functions/validar.php",true);
					 ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");//Cabecera obligatoria para method POST
					 var datos="parametros=val_dni&value="+valor3;
					 ajax.send(datos);          //enviamos parametros
					 ajax.onreadystatechange=
					 function() {
						if (ajax.readyState==4) {
							divdatos.innerHTML = ajax.responseText
								if(ajax.responseText=="Este Documento ya se encuentra registrado" || ajax.responseText=="Este nombre de usuario ya se encuentra registrado"){
							
									obj.disabled = 'true';
									obj.className="none";
									valor3=""
									return false;
								}else{
									obj.disabled = '';
									obj.className="btn";
								}
						}
					}
				}
			}
			var valor4 = document.getElementById("u_fn").value;
			var valor5 = document.getElementById("u_dom").value;
			var valor6 = document.getElementById("u_fono").value;
			var valor7 = document.getElementById("u_email").value;
			if(valor7==""){
				valor7="-";
			}
			var valor8 = document.getElementById("u_user").value.replace(/^(\s|\&nbsp;)*|(\s|\&nbsp;)*$/g,"");
			
			if(web.indexOf("update")!=-1){
			
			}else{
				if(valor8!="" ){
					 var obj= document.getElementById('a_user')
					 ajax=Ajax();
					 ajax.open("POST", "functions/validar.php",true);
					 ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");//Cabecera obligatoria para method POST
					 var datos="parametros=val_login&value="+valor8;
					 ajax.send(datos);          //enviamos parametros
					 ajax.onreadystatechange=
					 function() {
						if (ajax.readyState==4) {
							divdatos.innerHTML = ajax.responseText
								if(ajax.responseText=="Este Documento ya se encuentra registrado" || ajax.responseText=="Este nombre de usuario ya se encuentra registrado"){
							
									obj.disabled = 'true';
									obj.className="none";
									valor8=""
									return false;
								}else{
									obj.disabled = '';
									obj.className="btn";
								}
						}
					}
				}
			}
			//var valor9 = document.getElementById("u_pas").value;
			var valor10 = document.getElementById("u_fi").value;
			var valor11 = document.getElementById("u_cart").value;
			var valor12 = document.getElementById("u_niv").value;
			var valor13 = document.getElementById("u_est").value;
			var valor14 = document.getElementById("dpto").value;
			var valor15 = document.getElementById("prov").value;
			var valor16 = document.getElementById("dist").value;
			var valor17 = document.getElementById("u_prove").value;
				if(valor17==1){
					var valor11=1;
				}
			var valor18 = document.getElementById("u_mod").value;
			var valor19 = document.getElementById("u_emp").value;
			var valor20 = document.getElementById("u_tur").value;
			var valor21 = document.getElementById("u_hor_s").value;
			var valor22 = document.getElementById("u_hor_lv").value;
			
		}


		if(document.getElementById("p_nomb") ){
			var valor = document.getElementById("p_nomb").value;
			var valor2 = document.getElementById("p_doc").value
			var valor3 = document.getElementById("p_pers").value;
			var valor4 = document.getElementById("p_ndoc").value;
				if(valor4!=""){
					var obj= document.getElementById('a_pr')
					 ajax=Ajax();
					 ajax.open("POST", "functions/validar.php",true);
					 ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");//Cabecera obligatoria para method POST
					 var datos="parametros=val_dni_pr&value="+valor4;
					 ajax.send(datos);          //enviamos parametros
					 ajax.onreadystatechange=
					 function() {
						if (ajax.readyState==4) {
							divdatos.innerHTML = ajax.responseText
								if(ajax.responseText=="Este Documento ya se encuentra registrado" || ajax.responseText=="Este nombre de usuario ya se encuentra registrado"){
							
									obj.disabled = 'true';
									obj.className="none";
									return false;
								}else{
									obj.disabled = '';
									obj.className="btn";
								}
						}
					}
				}
			var valor5 = document.getElementById("p_tel").value;
			var valor6 = document.getElementById("p_cont").value;
			var valor7 = document.getElementById("p_obs").value;
			var valor8 = document.getElementById("p_estado").value;
			var valor9 = document.getElementById("dpto").value;
			var valor10 = document.getElementById("prov").value;
			var valor11 = document.getElementById("dist").value;
		}

		if(document.getElementById("seg")){
			var valor = document.getElementById("seg").value;
				if(document.getElementById("sg_estado")){
					var valor2 = document.getElementById("sg_estado").value;
				}
		}

		if(document.getElementById("pro")){
			var valor = document.getElementById("pro").value;
			var valor2 = document.getElementById("p_prove").value;
			var valor3 = document.getElementById("p_seg").value;
				if(document.getElementById("p_estado")){
					var valor4 = document.getElementById("p_estado").value;
				}
		}

		if(document.getElementById("cart")){
			var valor = document.getElementById("cart").value;
			var valor2 = document.getElementById("c_prove").value;
				if(document.getElementById("c_estado")){
					var valor3 = document.getElementById("c_estado").value;
				}
			
		}

		if(document.getElementById("conta")){
			var valor = document.getElementById("conta").value;
			var valor1=document.getElementById("tp_cont").value;
				if(document.getElementById("ctb_estado")){
					var valor2 = document.getElementById("ctb_estado").value;
				}
		}

		if(document.getElementById("ubic")){
			var valor = document.getElementById("ubic").value;
				if(document.getElementById("ub_estado")){
					var valor2 = document.getElementById("ub_estado").value;
				}
		}

		if(document.getElementById("or_d")){
			var valor = document.getElementById("or_d").value;
				if(document.getElementById("od_estado")){
					var valor2 = document.getElementById("od_estado").value;
				}
		}

		if(document.getElementById("or_t")){
			var valor = document.getElementById("or_t").value;
				if(document.getElementById("ot_estado")){
					var valor2 = document.getElementById("ot_estado").value;
				}
		}

		if(document.getElementById("gru_g")){
			var valor = document.getElementById("gru_g").value;
				if(document.getElementById("g_estado")){
					var valor2 = document.getElementById("g_estado").value;
				}
		}

		if(document.getElementById("grup_r")){
			var valor = document.getElementById("grup_r").value;
			var valor2 = document.getElementById("result").value;
			
			if(document.getElementById("no_r")){
			
				if(document.getElementById("no_r").checked){
					var valor3 = document.getElementById("no_r").value;
				}
			}
			
			if(document.getElementById("si_r")){
			
				if(document.getElementById("si_r").checked){
					var valor3 = document.getElementById("si_r").value;

				}
			}
			
			if(document.getElementById("r_estado")){
				var valor4 = document.getElementById("r_estado").value;
			}
			
			if(document.getElementById("no_rc")){
			
				if(document.getElementById("no_rc").checked){
					var valor5 = document.getElementById("no_rc").value;
				}
			}
			
			if(document.getElementById("si_rc")){
			
				if(document.getElementById("si_rc").checked){
					var valor5 = document.getElementById("si_rc").value;

				}
			}
		}

		if(document.getElementById("just")){
			var valor = document.getElementById("just").value
			var valor2 = document.getElementById("id_r").value;
				if(document.getElementById("j_estado")){
					var valor3 = document.getElementById("j_estado").value;
				}
			var valor4 = document.getElementById("ps_just").value;
		}
		
		if(document.getElementById("id_rs")){
			var valor = document.getElementById("id_rs").value;
			var valor2 = document.getElementById("id_c").value;
			if(document.getElementById("rc_estado")) {
			   var valor3 = document.getElementById("rc_estado").value;
				}
		 }
		 if(document.getElementById("id_co")){
			var valor = document.getElementById("id_co").value;
			var valor2 = document.getElementById("id_c").value;
			if(document.getElementById("cc_estado")) {
			   var valor3 = document.getElementById("cc_estado").value;
				}
		 }
		 
		 if(document.getElementById("id_ac")){
			var valor = document.getElementById("id_ac").value;
			var valor2 = document.getElementById("id_c").value;
			if(document.getElementById("ac_estado")) {
			   var valor3 = document.getElementById("ac_estado").value;
				}
		 }
		 
		 if(document.getElementById("id_js")){
			var valor = document.getElementById("id_js").value;
			var valor2 = document.getElementById("id_c").value;
			if(document.getElementById("jc_estado")) {
			   var valor3 = document.getElementById("jc_estado").value;
				}
		 }
		 
		if(document.getElementById("plano")){
			var valor = document.getElementById("plano").value;
			var valor2 = document.getElementById("dpto").value;
			var valor3 = document.getElementById("prov").value;
			var valor4 = document.getElementById("dist").value;
		}

		if(document.getElementById("cuadr")){
			var valor = document.getElementById("cuadr").value;
			var valor2 = document.getElementById("c_plano").value;
			var valor3 = document.getElementsByName("cdr")
				total = valor3.length
				
				for(var i = 0; i <= total; i++) {
					if(valor3[i].checked){
						var valor3=valor3[i].value
						
						break;
					}
				}
	
		}
		
		if(document.getElementById("act")){
			var valor = document.getElementById("act").value;
			if(document.getElementById("act_est")){
				var valor2 = document.getElementById("act_est").value;
			}
		}
		
		if(document.getElementById("c_pre")){
			var valor = document.getElementById("c_pre").value;
			if(document.getElementById("c_p_estado")){
				var valor2 = document.getElementById("c_p_estado").value;
			}
		}
		
		if(document.getElementById("c_mpre")){
			var valor = document.getElementById("c_mpre").value;
				if(document.getElementById("c_pm_estado")){
					var valor2 = document.getElementById("c_pm_estado").value;
				}
		}
		
		if(document.getElementById("c_npisos")){
			var valor = document.getElementById("c_npisos").value;
				if(document.getElementById("c_ps_estado")){
					var valor2 = document.getElementById("c_ps_estado").value;
				}
		}
		
		if(document.getElementById("c_cpared")){
			var valor = document.getElementById("c_cpared").value;
				if(document.getElementById("c_cp_estado")){
					var valor2 = document.getElementById("c_cp_estado").value;
				}
		}
		
		if(document.getElementById("or_e")){
			var valor = document.getElementById("or_e").value;
				if(document.getElementById("oe_estado")){
					var valor2 = document.getElementById("oe_estado").value;
				}
		}
		
		if(document.getElementById("tpc")){
			var valor = document.getElementById("tpc").value;
				if(document.getElementById("tc_estado")){
					var valor2 = document.getElementById("tc_estado").value;
				}
		}
		
		if(document.getElementById("vald")){
			var valor = document.getElementById("vald").value;
				if(document.getElementById("vald_estado")){
					var valor2 = document.getElementById("vald_estado").value;
				}
		}
		
		 divdatos = document.getElementById('ykBodys');
		  
		 if(valor=="" || valor1=="" || valor2=="" || valor3=="" || valor4=="" || valor5=="" || valor6=="" 
			|| valor7=="" || valor8=="" || valor9=="" || valor10=="" || valor11==""	|| valor12==""
			|| valor13=="" || valor14==""  || valor15==""  || valor16=="" || valor17==""  || valor18=="" || valor19=="" || valor20=="" || valor21=="" || valor22==""){
			  divdatos.innerHTML = "Ingrese Datos Por Favor";
			  return false;
		 }
		 
         ajax=Ajax();
         ajax.open("POST", "functions/insert.php",true);
         ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");//Cabecera obligatoria para method POST
         var datos="parametros="+web+"&value1="+valor1+"&&value="+valor+"&&value2="+valor2+"&&value3="+valor3
         +"&&value4="+valor4+"&&value5="+valor5+"&&value6="+valor6+"&&value7="+valor7+"&&value8="+valor8
         +"&&value9="+valor9+"&&value10="+valor10+"&&value11="+valor11+"&&value12="+valor12+"&&value13="+valor13
         +"&&value14="+valor14+"&&value15="+valor15+"&&value16="+valor16+"&&value17="+valor17+"&&value18="+valor18+"&&value19="+valor19+"&&value20="+valor20+"&&value21="+valor21
		 +"&&value22="+valor22;
         ajax.send(datos);          //enviamos parametros
         ajax.onreadystatechange=
            function() {
                if (ajax.readyState==4) {
                    divdatos.innerHTML = ajax.responseText
					
					document.getElementById("atra").click()
                    //setInterval("m2('nivel')",1000);
                   //clearInterval(setInterval("m2('nivel')",1000));
                }
            }
	}
//--------------------------------------------------------------------
	function pdf(){
		var iddpto=document.getElementById('dpto').value
		var idprov=document.getElementById('prov').value
		var iddist=document.getElementById('dist').value
			ajax=Ajax();
			ajax.open("GET", "pdf/pdf.php?"+"&&iddpto="+iddpto+"&&idprov="+idprov+"&&iddist="+iddist,true);
			ajax.onreadystatechange=
				function() {
					if (ajax.readyState==4) {
						var web="pdf/pdf.php?iddpto="+iddpto+"&&idprov="+idprov+"&&iddist="+iddist
						window.open(web,"_blank","GestionSistema", "chrome,centerscreen,resizable");
					}
				}
			ajax.send(null)
	}
//--------------------------------------------------------------------	
	
	function excel(){
		var iddpto=document.getElementById('dpto').value
		var idprov=document.getElementById('prov').value
		var iddist=document.getElementById('dist').value
			ajax=Ajax();
			ajax.open("GET", "excel/excel.php?"+"&&iddpto="+iddpto+"&&idprov="+idprov+"&&iddist="+iddist,true);
			ajax.onreadystatechange=
				function() {
					if (ajax.readyState==4) {
						var web="excel/excel.php?iddpto="+iddpto+"&&idprov="+idprov+"&&iddist="+iddist
						window.open(web,"_blank","GestionSistema", "chrome,centerscreen,resizable");
					}
				}
			ajax.send(null)
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

function buscar_rpp(){
	var venc;
	if (document.getElementById('ven1').checked){venc=1;}
	if (document.getElementById('ven2').checked){venc=2;}
	if (document.getElementById('ven3').checked){venc=3;}
	var cart=document.getElementById('cart').value
	if(cart==""){alert("Elija una Cartera Por Favor.");return false;}
	//var venc=document.getElementById('ven').value;
	//document.getElementById("consulta").style.visibility="visible";
	divResultado = document.getElementById('t1');
	ajax=Ajax();
	ajax.open("GET","functions/tbl_rpp.php?ven="+venc+"&cartera="+cart,true);
	divResultado.innerHTML = "<center><img src='imag/icono-cargando.gif'/></center>";
	setTimeout("",15000);
	ajax.onreadystatechange=
	function() {
				if (ajax.readyState==4) {
					divResultado.innerHTML = ajax.responseText
				}
				
			}
	ajax.send(null)
	return false;
}

	function abrirventana()
	{
		var pe = document.getElementById("periodo").value
		var pr = document.getElementById("u_prove").value
		var ca = document.getElementById("u_cart").value
		if(pe==""){
			alert("Por favor Elija un periodo"); 
			document.getElementById("periodo").focus();
			return false;
		}
		
		if(pr==""){
			alert("Por favor Elija un Proveedor"); 
			document.getElementById("u_prove").focus();
			return false;
		}
		
		if(ca==""){
			alert("Por favor Elija un Cartera"); 
			document.getElementById("u_cart").focus();
			return false;
		}
			
		window.open("functions/txt_dmora.php?pe="+pe+"&ca="+ca,"mywindow","menubar=1,resizable=1,width=400,height=600");
	}