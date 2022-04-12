function s_fono(ind){
	
		if(ind==13){
			ajax=Ajax();
						ajax.open("GET","functions/sel_ac_rs.php?idcampo="+document.getElementById('prov').value+"&idac="+ind ,true);

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
			//return false;
		}
	


}

function s_acrs(datos) {
		var cart = eval(datos);
		
			var obj = document.getElementById('resultado_gs');

			obj.length = 0;
			obj.options[0] = new Option('--Seleccionar--', '');
			for(contador = 0; contador < cart.length; contador++) {
				obj.options[contador + 1] = new Option(cart[contador][1], cart[contador][0]);
			}
			
	}


function  filtro_rsg(){
	var rs_gs=document.getElementById("resultado_gs").value.split("-")
		rs_gs=rs_gs[1]
		//alert(rs_gs)
	if( rs_gs==32 || rs_gs==33 || rs_gs==34 || rs_gs==44 || rs_gs==37 || rs_gs==352 || rs_gs==353 || rs_gs==318){
			document.getElementById("g_tcg_c").style.visibility = "hidden";
			document.getElementById("g_tcg_c").style.position = "absolute";
	}else{
			document.getElementById("g_tcg_c").style.visibility = "visible";
			document.getElementById("g_tcg_c").style.position = "relative";
	}
		
	if( rs_gs==31 || rs_gs==43 || rs_gs==32 || rs_gs==33 || rs_gs==34 || rs_gs==44 || rs_gs==49 || rs_gs==36 || rs_gs==37 || rs_gs==347 || rs_gs==318  || rs_gs==352 || rs_gs==353 || rs_gs==323 ){
			document.getElementById("detalle_s_v").style.visibility = "hidden";
			document.getElementById("detalle_s_v").style.position = "absolute";
	}else{
			document.getElementById("detalle_s_v").style.visibility = "visible";
			document.getElementById("detalle_s_v").style.position = "relative";
	}
		
	if( rs_gs==31 || rs_gs==43 || rs_gs==35 || rs_gs==36 || rs_gs==49  || rs_gs==347 || rs_gs==328 || rs_gs==323){
			document.getElementById("gc_dg_ver").style.visibility = "hidden";
			document.getElementById("gc_dg_ver").style.position = "absolute";
	}else{
			document.getElementById("gc_dg_ver").style.visibility = "visible";
			document.getElementById("gc_dg_ver").style.position = "relative";
	}
	
	if( rs_gs==32 || rs_gs==33 || rs_gs==34 || rs_gs==44 || rs_gs==37 || rs_gs==352 || rs_gs==353 || rs_gs==318){
			document.getElementById("gc_dg_ver").style.visibility = "hidden";
			document.getElementById("gc_dg_ver").style.position = "absolute";
			document.getElementById("dt_dr").style.visibility = "hidden";
			document.getElementById("dt_dr").style.position = "absolute";
	
	}else{
			/*if( rs_gs!=31 || rs_gs!=43 || rs_gs!=35 || rs_gs!=36 ){
			document.getElementById("gc_dg_ver").style.visibility = "visible";
			document.getElementById("gc_dg_ver").style.position = "relative";
			}*/
			document.getElementById("dt_dr").style.visibility = "visible";
			document.getElementById("dt_dr").style.position = "relative";
	}
	
	if(rs_gs==38 || rs_gs==39 || rs_gs==248 || rs_gs==351){
		document.getElementById("rs_camp").style.visibility = "visible";
		document.getElementById("rs_camp").style.position = "relative";
	}else{
		document.getElementById("rs_camp").style.visibility = "hidden";
		document.getElementById("rs_camp").style.position = "absolute";
	
	}
	
	//-----------
	
	if(rs_gs==73 || rs_gs==74)
	{
	 document.getElementById("rs_camp").style.visibility = "hidden";
	 document.getElementById("rs_camp").style.position = "absolute";
	 if(rs_gs==74){document.getElementById("gc_dg_ver").style.visibility="hidden";document.getElementById("gc_dg_ver").style.position = "absolute";}
	}else if(rs_gs==38 || rs_gs==39 || rs_gs==248 || rs_gs==351){
	 document.getElementById("rs_camp").style.visibility = "visible";
	 document.getElementById("rs_camp").style.position = "relative";
	}
	
	//-----------

}



function tp_cont(){
	var id_cart=document.getElementById("id_c").value
	var id=document.getElementById("gca_id").value

	if(id_cart==""){ return false;}
		
	ajax=Ajax();
	ajax.open("GET", "functions/tp_cont.php?id_cart="+id_cart+"&id="+id,true);
	ajax.onreadystatechange=
				function() {
					if (ajax.readyState==4) {
						
						total=ajax.responseText;
						var obj2 = document.getElementById('g_tcg')
						
						if(total.indexOf(',')!=-1){
							tot= total.split(",");
						}else{
							tot=new Array() ;
							tot[0]=total;
						}

						for(cont = 0; cont < tot.length; cont++) {
							tp_cart=tot[cont].split("-")
							obj2.options[cont + 1] = new Option(tp_cart[1], tp_cart[0]);
						}
					}
				}
	ajax.send(null)	
	
	if(id_cart==51){
	//	s_fono(13);
	}

}

function provee(){
		var id=document.getElementById("gca_id").value
	
		ajax=Ajax();
		
		ajax.open("GET", "functions/digita.php?proveedor=ok&id_gca="+id,true);
		ajax.onreadystatechange=
				function() {
					
					if (ajax.readyState==4) {
							//alert(ajax.responseText);
							var todo=ajax.responseText;
							
							var datos = "uno";
							if(todo.indexOf(',')!=-1){
								datos = ajax.responseText.split(",");
								
							}else{
								datos=new Array() ;
								datos[0]=ajax.responseText;
							}

							
							var obj = document.getElementById('prov');
							
							obj.length = 0;
							obj.options[0] = new Option('--Seleccionar--', '');
							contt=0;
							for(contador = 0; contador < datos.length; contador++) {
										sp=datos[contador].split("*");
										prov=sp[0].split(".")
										//alert(sp[0]+"-"+sp[1])
										obj.options[contador + 1] = new Option(prov[1], prov[0]);

											var obj2 = document.getElementById('id_c')
											cart="uno"
											
											
											if(sp[1].indexOf('-')!=-1){
												tot=sp[1].split("-");
											}else{
												tot=new Array() ;
												tot[0]=sp[1];
											}
											
											for(cont=0; cont < tot.length; cont++) {
												cart=tot[cont].split(".")
												++contt;
												obj2.options[contt] = new Option(cart[1], cart[0]);
											}
							}
						
					}
				}
		ajax.send(null)	
							
		
	
}

function gest_digi(){
		var id=document.getElementById("gca_id").value
		if(id==""){
					
					document.getElementById("historial").innerHTML = ""
					document.getElementById("rpta_t_c").innerHTML = ""
					document.getElementById("rpta_d_c").innerHTML = ""
					
					document.getElementById("gca_id").value=''
					document.getElementById("clientes").value=''
					//document.getElementById("g_fg").value=''
					document.getElementById("g_hg").value=''
					document.getElementById("detalle_s").value=''
					document.getElementById("sol_s").value=''
					document.getElementById("dol_s").value=''
					e_ubi=document.getElementById("agente_gs").childNodes
					e_ubi[1].selected='true'
					e_ubi=document.getElementById("resultado_gs").childNodes
					e_ubi[1].selected='true'
					e_ubi=document.getElementById("g_tcg").childNodes
					e_ubi[1].selected='true'
					e_ubi=document.getElementById("gc_dg").childNodes
					e_ubi[1].selected='true'
					
					e_ubi2=document.getElementById("g_drg")
					e_ubi2.length = 0;
					e_ubi2.options[0] = new Option('Seleccione..', '');
					
					e_ubi=document.getElementById("gca_tp").childNodes
					e_ubi[1].selected='true'
					e_ubi=document.getElementById("gca_m").childNodes
					e_ubi[1].selected='true'
					e_ubi=document.getElementById("gca_nrp").childNodes
					e_ubi[1].selected='true'
					e_ubi=document.getElementById("gca_c").childNodes
					return false;
					
		}
		
		
								
			
		
		ajax=Ajax();
			ajax.open("GET", "functions/digita.php?id_gca="+id,true);
			ajax.onreadystatechange=
				function() {
					if (ajax.readyState==4) {
						var todo=ajax.responseText
						var rep = todo.split("<br/>")
						document.getElementById("clientes").value=rep[1]
						
						var ob_rgs =document.getElementById("g_drg")
						var rpta=rep[0]
						var dato = rpta.split("*")
								
						ob_rgs.length = 0;
						ob_rgs.options[0] = new Option('Seleccione..', '');
						
						
							for(contador = 0; contador < dato.length; contador++){
									if(!dato[contador]){
										continue;
									}
									var datos=dato[contador].split("/")		
									ob_rgs.options[contador + 1] = new Option(datos[1], datos[0]);
							}
							
							if(id!=""){
							
								document.getElementById("tel_ca").click()
								
							}
					}
				}
			ajax.send(null)
			if(document.getElementById("dir_gca").value=="Corregir"){document.getElementById("dir_gca").value='Grabar'}
				document.getElementById("camp_dir").value= ''
				e_ubi=document.getElementById("camp_ubi").childNodes
				e_ubi[1].selected='true'
				e_ubi=document.getElementById("camp_od").childNodes
				e_ubi[1].selected='true'
				
				document.getElementById("camp_fono").value= ''
				document.getElementById("pre_fono").value= ''
				t_ubi=document.getElementById("camp_ot").childNodes
				t_ubi[1].selected='true'
				
			

}
function c_ver_dt(tipo){
		dat=document.getElementById("rpta_t_c")
		var id=document.getElementById("gca_id").value
			
			ajax=Ajax();
			ajax.open("GET", "functions/digita.php?value="+id+"&tipo=tel",true);
			dat.innerHTML = "<center><img src='imag/cargando.gif'/></center>"
			
			ajax.onreadystatechange=
				function() {
					if (ajax.readyState==4) {
						dat.innerHTML=ajax.responseText
						document.getElementById("dir_ca").click();
					}else{
						dat.innerHTML = "<center><img src='imag/cargando.gif'/></center>"
					
					}
				}
			ajax.send(null)
			
}

function c_ver_dt2(tipo){
		dat2=document.getElementById("rpta_d_c")
		var id=document.getElementById("gca_id").value
			
			ajax=Ajax();
			ajax.open("GET", "functions/digita.php?value="+id+"&tipo=dir",true);
			dat2.innerHTML = "<center><img src='imag/cargando.gif'/></center>"
			
			ajax.onreadystatechange=
				function() {
					if (ajax.readyState==4) {
						dat2.innerHTML=ajax.responseText
						document.getElementById("resu_ca").click();
					}else{
						dat2.innerHTML = "<center><img src='imag/cargando.gif'/></center>"
					
					}
				}
			ajax.send(null)
			
}

function c_ver_r(tipo){
		dat3=document.getElementById("historial")
		var id=document.getElementById("gca_id").value
			
			ajax=Ajax();
			ajax.open("GET", "functions/digita.php?value="+id+"&tipo=result",true);
			dat3.innerHTML = "<center><img src='imag/cargando.gif'/></center>"
			
			ajax.onreadystatechange=
				function() {
					if (ajax.readyState==4) {
						dat3.innerHTML=ajax.responseText
						document.getElementById("prove_ca").click()
						
					}else{
						dat3.innerHTML = "<center><img src='imag/cargando.gif'/></center>"
					
					}
				}
			ajax.send(null)
			
}

function camp_insert(tipo){
	var id=document.getElementById("gca_id").value
	if(tipo=="tel"){
		dat2=document.getElementById("rpta_t_c")
		var fono=document.getElementById("camp_fono").value
		var o_fono=document.getElementById("camp_ot").value
		var pre_fono=document.getElementById("pre_fono").value
		var a_fono=document.getElementById("a_fono").value
			nr1=fono.substring(0,1)
			
			if(nr1==0){alert("El Nro Telefonico no debe empezar con cero");return false;}
			if(nr1==9){ 
				if(fono.length<9){ alert("Celular Incompleto");return false;}
				if(fono.length>9){ alert("Celular Excede el Nro de digitos");return false;}
				if(pre_fono!=""){ alert("Prefijo Debe Estar Vacio");return false;}
				if(a_fono!=""){ alert("Anexo Debe Estar Vacio");return false;}
				
			}
			
			if(fono.length==6){
				if(pre_fono==""){
					alert("Escriba el prefijo porfavor");
					return false;
				}
			}
			
			if(fono.length==7){
				
			}
			

			if(pre_fono==""){ pre_fono="*"}
			if(a_fono==""){ a_fono="0"}
	}else if(tipo=="dir"){
		dat2=document.getElementById("rpta_d_c")
		var dir=document.getElementById("camp_dir").value
		var o_dir=document.getElementById("camp_od").value
		var ubi=document.getElementById("camp_ubi").value	
	}		
			ajax=Ajax();
	if(document.getElementById("dir_gca").value=="Corregir"){var id_up=document.getElementById("dir_up").value}else{var id_up="-"}		
	if(document.getElementById("bt_telgsc").value=="Corregir"){var id_up2=document.getElementById("telup").value}else{var id_up2="-"}
	if(tipo=="tel"){ajax.open("GET", "functions/digita.php?value="+id+"&tipo=tel&func="+tipo+"&fono="+fono+"&o_fono="+o_fono+"&pre_fono="+pre_fono+"&id_up="+id_up2+"&anx="+a_fono,true);}
	if(tipo=="dir"){ajax.open("GET", "functions/digita.php?value="+id+"&tipo=dir&func="+tipo+"&dir="+dir+"&o_dir="+o_dir+"&ubi="+ubi+"&id_up="+id_up,true);}
			dat2.innerHTML = "<center><img src='imag/cargando.gif'/></center>"
			
			ajax.onreadystatechange=
				function() {
					if (ajax.readyState==4) {
						dat2.innerHTML=ajax.responseText
						if(document.getElementById("dir_gca").value=="Corregir"){document.getElementById("dir_gca").value='Grabar'}
						if(document.getElementById("bt_telgsc").value=="Corregir"){document.getElementById("bt_telgsc").value='Grabar'}
							
									document.getElementById("camp_dir").value= ''
									e_ubi=document.getElementById("camp_ubi").childNodes
									e_ubi[1].selected='true'
									e_ubi=document.getElementById("camp_od").childNodes
									e_ubi[1].selected='true'
									document.getElementById("pre_fono").value=''
									document.getElementById("camp_fono").value= ''
									e_ubi=document.getElementById("camp_ot").childNodes
									e_ubi[1].selected='true'
									document.getElementById("telup").value=""
								
							
						
					}else{
						dat2.innerHTML = "<center><img src='imag/cargando.gif'/></center>"
					
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
			
        if(rs_gs==38){
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
           // divdatos.innerHTML="La fecha de compromiso no puede ser menor o igual a de la fecha gestion";
            return 0;
        }else{
			divdatos.innerHTML="";
		}
}
function gs_campo(){

	var divdatos=document.getElementById("e_gg")
	var agen=document.getElementById("agente_gs").value
	var id_cl=document.getElementById("gca_id").value
	var cl=document.getElementById("clientes").value
	var prove=document.getElementById("prov").value
	var cart=document.getElementById("id_c").value
	var fec=document.getElementById("g_fg").value
	var hor=document.getElementById("g_hg").value
	var rs_ges=document.getElementById("resultado_gs").value
		rs_ges=rs_ges.split("-")
		rs_ges=rs_ges[1]
		var t_cont=document.getElementById("g_tcg").value
		var det_r=document.getElementById("detalle_s").value
		var sol=document.getElementById("sol_s").value
		var dol=document.getElementById("dol_s").value
	//var pa=document.getElementById("g_pg").value
	var ubi=document.getElementById("gc_dg").value
	var dir=document.getElementById("g_drg").value
	var dir_t=document.getElementsByName("gca_dt")
		total = dir_t.length
		for(var i = 0; i <= total; i++) {
					if(dir_t[i].checked){
						var dir_t=dir_t[i].value
						break
					}
		}

	var t_pre=document.getElementById("gca_tp").value
	var m_pre=document.getElementById("gca_m").value
	var n_piso=document.getElementById("gca_nrp").value
	var c_prd=document.getElementById("gca_c").value
	
	var d_cdr=document.getElementById("g_d_cd").value
	var obs=det_r+"*"+sol+"*"+dol+"*"+document.getElementById("g_obsg").value
	
	if(d_cdr!=""){
		var obs=det_r+"*"+sol+"*"+dol+"*"+document.getElementById("g_obsg").value+"*"+d_cdr
	}
	//alert(rs_ges);
	if( rs_ges==32 || rs_ges==33 || rs_ges==34 || rs_ges==44 || rs_ges==37 || rs_ges==31 || rs_ges==43 || rs_ges==35 || rs_ges==36 || rs_ges==49){
		ubi="0";
	}
	
	if( rs_ges==32 || rs_ges==33 || rs_ges==34 || rs_ges==44 || rs_ges==37 ){	
		t_pre="0";
		m_pre="0";
		n_piso="0";
		c_prd="0";
	}
	
	if( rs_ges==32 || rs_ges==33 || rs_ges==34 || rs_ges==44 || rs_ges==37){
		t_cont=17;
	}
	
	fec_conf='0'
	var imp_comp=0;	
	if(rs_ges==38 || rs_ges==39){
		fec_conf= document.getElementById("desde").value
		if(fec_conf==""){
			 divdatos.innerHTML = "Ingresar Fecha de Confirmacion";
			 return false;
		}

		if(sol=="" &&  dol==""){
				
				divdatos.innerHTML = "Ingresar Importe ";
			    return false;
		}
		if(sol==""){var imp_comp=dol;}else{ var imp_comp=sol;}
	}
	
	if(agen=="" || id_cl=="" || cl=="" || fec=="" || hor=="" || rs_ges=="" 
			|| ubi=="" || dir=="" || t_pre=="" || m_pre==""	|| n_piso==""
			|| c_prd=="" || prove=="" || cart==""){
				
			  divdatos.innerHTML = "Faltan Datos a Ingresar";
			  return false;
	}
	
	ajax=Ajax();
         ajax.open("POST", "functions/gestiones.php",true);
         ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
         var datos="tipo=campo&value1="+agen+"&value2="+id_cl+"&value3="+cl
         +"&value4="+fec+"&value5="+hor+"&value6="+rs_ges+"&value8="+ubi
         +"&value9="+dir+"&value10="+t_pre+"&value11="+m_pre+"&value12="+n_piso+"&value13="+c_prd
         +"&value14="+dir_t+"&value15="+obs+"&value16="+t_cont+"&value17="+cart+"&value18="+fec_conf+"&value19="+imp_comp;
         ajax.send(datos);         
         ajax.onreadystatechange=
            function() {
                if (ajax.readyState==4) {
					var fc = new Date() 
					
					divdatos.innerHTML = "<font color='red'>Dato Registrado Correctamente</font>";
					document.getElementById("historial").innerHTML = ""
					document.getElementById("rpta_t_c").innerHTML = ""
					document.getElementById("rpta_d_c").innerHTML = ""
					
					document.getElementById("gca_id").value=''
					document.getElementById("clientes").value=''
					mes=fc.getMonth()+1;
					if(mes<10){mes="0"+mes;}
					document.getElementById("g_fg").value=fc.getFullYear()+"-"+mes+"-"
					document.getElementById("g_hg").value=''
					document.getElementById("detalle_s").value=''
					document.getElementById("sol_s").value=''
					document.getElementById("dol_s").value=''
						document.getElementById("camp_dir").value= ''
						e_ubi=document.getElementById("camp_ubi").childNodes
						e_ubi[1].selected='true'
						e_ubi=document.getElementById("camp_od").childNodes
						e_ubi[1].selected='true'
						
						document.getElementById("camp_fono").value= ''
						document.getElementById("pre_fono").value= ''
						t_ubi=document.getElementById("camp_ot").childNodes
						t_ubi[1].selected='true'
					document.getElementById("g_obsg").value=''
					e_ubi=document.getElementById("agente_gs").childNodes
					e_ubi[1].selected='true'
					e_ubi=document.getElementById("resultado_gs").childNodes
					e_ubi[1].selected='true'
					e_ubi=document.getElementById("g_tcg").childNodes
					e_ubi[1].selected='true'
					e_ubi=document.getElementById("gc_dg").childNodes
					e_ubi[1].selected='true'
					
					e_ubi2=document.getElementById("g_drg")
					e_ubi2.length = 0;
					e_ubi2.options[0] = new Option('Seleccione..', '');
					
					e_ubi=document.getElementById("gca_tp").childNodes
					e_ubi[1].selected='true'
					e_ubi=document.getElementById("gca_m").childNodes
					e_ubi[1].selected='true'
					e_ubi=document.getElementById("gca_nrp").childNodes
					e_ubi[1].selected='true'
					e_ubi=document.getElementById("gca_c").childNodes
					e_ubi[1].selected='true'
					document.getElementById("agente_gs").focus()
					var obj = document.getElementById('prov');
					obj.length = 0;
					obj.options[0] = new Option('--Seleccionar--', '');
					var obj = document.getElementById('id_c');
					obj.length = 0;
					obj.options[0] = new Option('--Seleccionar--', '');
					var obj = document.getElementById('g_tcg');
					obj.length = 0;
					obj.options[0] = new Option('--Seleccionar--', '');
					
					
                }
            }
	 
}

function disa(nro,ubi,od,tipo){

	if(tipo=='dir'){
	
		var che=document.getElementsByName("editar")
		
		var total = che.length
			for(var i = 0; i <= total; i++) {
				if(che[i].id=="ed"+nro){
					//alert("a")
					
				}
				if(i==total-1){
					break
				}
			}
			
			var obj=document.getElementById("d"+nro)
						var obj2=obj.parentNode
							obj2=obj2.childNodes
							total2=obj2.length
							
			for(var i = 0; i <= total; i++) {
				if(che[i].id=="ed"+nro){
					if(che[i].checked==0){
						
						for(i=0;i<total2;i++){
							//obj2[i].disabled = 'true';
								document.getElementById("camp_dir").value= ''
								e_ubi=document.getElementById("camp_ubi").childNodes
								e_ubi[1].selected='true'
								e_ubi=document.getElementById("camp_od").childNodes
								e_ubi[1].selected='true'
							//obj2[i].style.backgroundColor = "#F9FCFF";
							//obj2[i].style.color = "black";
							document.getElementById("dir_gca").value='Grabar'
							document.getElementById("dir_up").value=""
							i++
						}
						return false;
						
					}
					che[i].checked=1
				}else{
					/*for(i=0;i<total2;i++){
					
						alert(total2)
						obj2[i].style.backgroundColor = "#F9FCFF";
						obj2[i].style.color = "black";
						i++
					}*/
					
					che[i].checked=0
				}
				if(i==total-1){
					break
				}
			}
		
		var obj=document.getElementById("d"+nro)
		var obj2=obj.parentNode
			obj2=obj2.childNodes
			total=obj2.length

			if(document.getElementById("ed"+nro).checked){
				for(i=0;i<total;i++){
					//obj2[i].disabled = '';
					if(i==0){
						document.getElementById("camp_dir").value=obj2[1].value
					}
					if(i==2){
						e_ubi=document.getElementById("camp_ubi").childNodes
						total_ubi=e_ubi.length
						for(z=3;z<=total_ubi;z++){
							if(ubi==e_ubi[z].value){
								e_ubi[z].selected='true'
								break
							}
						}
					}
					if(i==4){
						e_od=document.getElementById("camp_od").childNodes
						total_od=e_od.length
						for(z=3;z<=total_od;z++){
							if(od==e_od[z].value){
								e_od[z].selected='true'
								break
							}
						}
					}
					//obj2[i].style.backgroundColor = "white";
					//obj2[i].style.color = "#6495ED";
					document.getElementById("dir_gca").value='Corregir'
					document.getElementById("dir_up").value=nro
					i++
					
				}
			}
	}else{
		var che=document.getElementsByName("editarF")
		
		var total = che.length
			for(var i = 0; i <= total; i++) {
				if(che[i].id=="ef"+nro){
					//	alert(che[i])
					
				}
				if(i==total-1){
					break
				}
			}
			
			var obj=document.getElementById("f"+nro)
						var obj2=obj.parentNode
							obj2=obj2.childNodes
							total2=obj2.length
							
			for(var i = 0; i <= total; i++) {
				if(che[i].id=="ef"+nro){
					if(che[i].checked==0){
						
						for(i=0;i<total2;i++){
							//obj2[i].disabled = 'true';
								document.getElementById("pre_fono").value=''
								document.getElementById("camp_fono").value= ''
								//e_ubi=document.getElementById("camp_ubi").childNodes
								//e_ubi[1].selected='true'
								e_ubi=document.getElementById("camp_ot").childNodes
								e_ubi[1].selected='true'
							//obj2[i].style.backgroundColor = "#F9FCFF";
							//obj2[i].style.color = "black";
							document.getElementById("bt_telgsc").value='Grabar'
							document.getElementById("telup").value=""
							i++
						}
						return false;
						
					}
					che[i].checked=1
				}else{
					/*for(i=0;i<total2;i++){
					
						alert(total2)
						obj2[i].style.backgroundColor = "#F9FCFF";
						obj2[i].style.color = "black";
						i++
					}*/
					
					che[i].checked=0
				}
				if(i==total-1){
					break
				}
			}
		
		var obj=document.getElementById("f"+nro)
		var obj2=obj.parentNode
			obj2=obj2.childNodes
			total=obj2.length

			if(document.getElementById("ef"+nro).checked){
				for(i=0;i<total;i++){
					//alert(obj2[i])
					//obj2[i].disabled = '';
					if(i==0){
						
						if(obj2[1].value.indexOf(" ")!=-1){
							pre=obj2[1].value.split(" ");
							document.getElementById("pre_fono").value=pre[0]
							document.getElementById("camp_fono").value=pre[1]
						}else{
							document.getElementById("pre_fono").value=''
							document.getElementById("camp_fono").value=obj2[1].value
							//pre[1]=obj2[1].value
						}
					
					}
					if(i==2){
						e_ubi=document.getElementById("camp_ot").childNodes
						total_ubi=e_ubi.length
						for(z=3;z<=total_ubi;z++){
							if(ubi==e_ubi[z].value){
								e_ubi[z].selected='true'
								break
							}
						}
					}
					/*if(i==4){
						e_od=document.getElementById("camp_od").childNodes
						total_od=e_od.length
						for(z=3;z<=total_od;z++){
							if(od==e_od[z].value){
								e_od[z].selected='true'
								break
							}
						}
					}*/
					//obj2[i].style.backgroundColor = "white";
					//obj2[i].style.color = "#6495ED";
					document.getElementById("bt_telgsc").value='Corregir'
					document.getElementById("telup").value=nro
					i++
					
				}
			}
	}
}

function cart(){
		
		if(document.getElementById('prov') && document.getElementById('prov').value!=""){
			var idprov=document.getElementById('prov').value
		}

			ajax=Ajax();
			ajax.open("GET", "sel_cart.php?"+"&&id_prove="+idprov,true);
			ajax.onreadystatechange=
				function() {
					if (ajax.readyState==4) {
						datos = ajax.responseText;
						if (idprov==""){
							var obj = document.getElementById('id_c');
							obj.length = 0;
							obj.options[0] = new Option('--Seleccionar--', '');
							return false;
						}
						m_cart(datos);

					}
				}
			ajax.send(null)
	}
//---------------------------------------------------------------------
	function m_cart(datos) {
		var cart = eval(datos);

		if(document.getElementById('id_c')){
			var obj = document.getElementById('id_c');
		}
			obj.length = 0;
			obj.options[0] = new Option('--Seleccionar--', '');
				for(contador = 0; contador < cart.length; contador++) {
					obj.options[contador + 1] = new Option(cart[contador][1], cart[contador][0]);
				}
	}