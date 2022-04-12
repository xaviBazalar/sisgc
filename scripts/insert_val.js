function insert(web){
     if(document.getElementById("nivel")){
     var valor = document.getElementById("nivel").value;
     var valor2 = document.getElementById("n_estado").value;}

    if(document.getElementById("moneda")){
     var valor = document.getElementById("moneda").value;}

    if(document.getElementById("parent")){
     var valor = document.getElementById("parent").value;}

    if(document.getElementById("pers")){
    var valor = document.getElementById("pers").value;
    var valor2 = document.getElementById("p_estado").value;}

    if(document.getElementById("doc")){
    var valor = document.getElementById("doc").value;
     var valor2 = document.getElementById("d_estado").value;}

    divdatos = document.getElementById('ykBodys');

        if(document.getElementById("peri")){
        var valor = document.getElementById("peri").value;
            if(document.getElementById("desde")){var valor2 = document.getElementById("desde").value;}
            if(document.getElementById("hasta")){var valor3 = document.getElementById("hasta").value;}
        }

        if(valor2>=valor3){
            divdatos.innerHTML="La fecha de inicio no puede ser mayor o igual a de la fecha final";
            return 0;

        }

    if(document.getElementById("u_name")){
    var valor = document.getElementById("u_name").value;
    var valor2 = document.getElementById("u_doc").value
    var valor3 = document.getElementById("u_ndoc").value;
    var valor4 = document.getElementById("u_fn").value;
    var valor5 = document.getElementById("u_dom").value;
    var valor6 = document.getElementById("u_fono").value;
    var valor7 = document.getElementById("u_email").value;
    var valor8 = document.getElementById("u_user").value;
    var valor9 = document.getElementById("u_pas").value;
    var valor10 = document.getElementById("u_fi").value;
    var valor11 = document.getElementById("u_cart").value;
    var valor12 = document.getElementById("u_niv").value;
    var valor13 = document.getElementById("u_est").value;
    var valor14 = document.getElementById("dpto").value;
    var valor15 = document.getElementById("prov").value;
    var valor16 = document.getElementById("dist").value;
    var valor17 = document.getElementById("u_prove").value;

    }


    if(document.getElementById("p_nomb")){
    var valor = document.getElementById("p_nomb").value;
    var valor2 = document.getElementById("p_doc").value
    var valor3 = document.getElementById("p_pers").value;
    var valor4 = document.getElementById("p_ndoc").value;
    var valor5 = document.getElementById("p_tel").value;
    var valor6 = document.getElementById("p_cont").value;
    var valor7 = document.getElementById("p_obs").value;
    var valor8 = document.getElementById("p_estado").value;
    var valor9 = document.getElementById("dpto").value;
    var valor10 = document.getElementById("prov").value;
    var valor11 = document.getElementById("dist").value;}

    if(document.getElementById("seg")){
    var valor = document.getElementById("seg").value;
    var valor2 = document.getElementById("sg_estado").value;}

    if(document.getElementById("pro")){
    var valor = document.getElementById("pro").value;
    var valor2 = document.getElementById("p_prove").value;
    var valor3 = document.getElementById("p_seg").value;
    var valor4 = document.getElementById("p_estado").value;}

    if(document.getElementById("cart")){
    var valor = document.getElementById("cart").value;
    var valor2 = document.getElementById("c_prove").value;
    var valor3 = document.getElementById("c_estado").value;}

    if(document.getElementById("conta")){
    var valor = document.getElementById("conta").value;}

    if(document.getElementById("ubic")){
    var valor = document.getElementById("ubic").value;}

    if(document.getElementById("or_d")){
    var valor = document.getElementById("or_d").value;
    var valor2 = document.getElementById("od_estado").value;}

    if(document.getElementById("or_t")){
    var valor = document.getElementById("or_t").value;}

    if(document.getElementById("gru_g")){
    var valor = document.getElementById("gru_g").value;}

    if(document.getElementById("grup_r")){
    var valor = document.getElementById("grup_r").value;}

    if(document.getElementById("result")){
    var valor2 = document.getElementById("result").value;}

    if(document.getElementById("just")){
    var valor = document.getElementById("just").value;
    if(document.getElementById("id_r")){
    var valor2 = document.getElementById("id_r").value;
    }
    }
    if(document.getElementById("id_rs")){
        var valor = document.getElementById("id_rs").value;
        if(document.getElementById("id_c")){
        var valor2 = document.getElementById("id_c").value;
        }
    }
    if(document.getElementById("plano")){
        var valor = document.getElementById("plano").value;
        var valor2 = document.getElementById("dpto").value;
        var valor3 = document.getElementById("prov").value;
        var valor4 = document.getElementById("dist").value;}

    if(document.getElementById("cuadr")){
        var valor = document.getElementById("cuadr").value;
        var valor2 = document.getElementById("c_plano").value;
    }

     divdatos = document.getElementById('ykBodys');

     if(valor=="" || valor2=="" || valor3=="" || valor4=="" || valor5=="" || valor6=="" || valor7=="" || valor8=="" || valor9=="" || valor10=="" || valor11==""
        || valor12=="" || valor13=="" || valor14==""  || valor15==""  || valor16=="" || valor17==""  ){
          divdatos.innerHTML = "Ingrese Datos Por Favor";
          return false;
     }
         ajax=Ajax();
         ajax.open("POST", "functions/insert.php",true);
         ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");//Cabecera obligatoria para method POST
         var datos="parametros="+web+"&&value="+valor+"&&value2="+valor2+"&&value3="+valor3
         +"&&value4="+valor4+"&&value5="+valor5+"&&value6="+valor6+"&&value7="+valor7+"&&value8="+valor8
         +"&&value9="+valor9+"&&value10="+valor10+"&&value11="+valor11+"&&value12="+valor12+"&&value13="+valor13
         +"&&value14="+valor14+"&&value15="+valor15+"&&value16="+valor16+"&&value17="+valor17;
         ajax.send(datos);          //enviamos parametros
         ajax.onreadystatechange=
            function() {
                if (ajax.readyState==4) {
                    divdatos.innerHTML = ajax.responseText
                    //setInterval("m2('nivel')",1000);
                   //clearInterval(setInterval("m2('nivel')",1000));
                }
            }
}