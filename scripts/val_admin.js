function users(){
	if(document.getElementById("u_niv").value==1 || document.getElementById("u_niv").value==""){
		var prv = document.getElementById("u_prove").getElementsByTagName("option")
		var total = prv.length
			for(i=0;i<=total;i++){
				if(prv[i].value==1){
					prv[i].selected=true;
						document.getElementById("u_prove").disabled=true
						document.getElementById("t_prov").style.visibility="hidden"
						document.getElementById("t_prov").style.display="none"
						var cart=document.getElementById("u_cart").getElementsByTagName("option")
						var total2 = cart.length
						
							for(z=0;z<=total2;z++){
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
			for(i=0;i<=total;i++){
				
				if(prv[i].value==""){
					prv[i].selected=true;
						var cart=document.getElementById("u_cart").getElementsByTagName("option")
						var total2 = cart.length
						
							for(z=0;z<=total2;z++){
							
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