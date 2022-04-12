  
	<!--head-->
    <script src="includes/calendar/src/js/jscal2.js"></script>
    <script src="includes/calendar/src/js/lang/es.js"></script>
    <link rel="stylesheet" type="text/css" href="includes/calendar/src/css/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="includes/calendar/src/css/border-radius.css" />
    <link rel="stylesheet" type="text/css" href="includes/calendar/src/css/steel/steel.css" />
    <link rel="stylesheet" type="text/css" href="table.css" />
    <!--/head-->
<script>
	function abrirventana()
	{
		var ano = document.getElementById("ano_afp").value
		var mes = document.getElementById("mes_afp").value
		
		if(ano==""){
			alert("Por favor Elija la Fecha"); 
			document.getElementById("ano_afp").focus();
			return false;}
		
		
		window.open("functions/txt_planillas.php?ano="+ano+"&mes="+mes,"mywindow","menubar=1,resizable=1,width=800,height=600");
	}
</script>
<center>
<form name="f1" id="f1" method="post">
<table>
<thead><h1>Declaracion de Planillas AFP</h1></thead>
<tr>
<td>CUSPP :</td><td><input type="text" size="12" name="cuspp" id="cuspp" /></td>
<td>D.N.I :</td><td><input type="text" size="10" name="dni" id="dni" /></td><tr><tr>
<td>Apellido Paterno: </td><td><input type="text" size="20" name="appt" id="appt" /></td>
<td>Apellido Materno: </td><td><input type="text" size="20" name="apma" id="apma" /></td><tr>
<td>Nombres: </td><td><input type="text" size="20" name="nom" id="nom" /></td>
<td>Tipo de Movimiento Personal: </td><td><select name="tmp" id="tmp">
						<option value="">Seleccione..</option>
						<option value="1">Inicio de Relacion</option>
						<option value="2">Termino de Relacion Lateral</option>
						<option value="3">Inicio subsidio por accidente de trabajo</option>
						<option value="4">Inicio de Licencia sin goce de haber</option>
						<option value="5">Inicio de periodo vacional pagado por adelantado</option>
						<option value="6">Reinicio de Relacion Laboral Plena</option>
						<option value="7">Inicio del trabajo de Riesgo</option>
						<option value="8">Fin del trabajo de riesgo</option>
						</select></td><tr>
<td>Fecha de Movimiento: </td><td><input size="13" id="f_date1" name="f_date1" class="text" value="<? echo $_POST["f_date1"] ?>" /><input type="button" id="f_btn1" class="button" />
<script type="text/javascript">//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() },
          showTime: false
      });
      cal.manageFields("f_btn1", "f_date1", "%d-%m-%Y");
//]]></script>
</td>
<td>Remuneracion Asegurable: </td><td><input type="text" size="9" name="ra" id="ra" onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" /></td><tr>
<td>Aporte voluntario con fin provisional: </td><td><input type="text" size="9" name="avcfp" id="avcfp" onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" /></td>
<td>Aporte voluntario sin fin provisional: </td><td><input type="text" size="9" name="avsfp" id="avsfp" onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" /></td><tr>
<td>Aporte voluntario del empleador: </td><td><input type="text" size="9" name="avde" id="avde" onkeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" /></td>
<td>Rubro de trabajador de alto riesgo: </td><td><select id="rubro" name="rubro">
							<option value="">Seleccione..</option>
							<option value="C">Construccion</option>
							<option value="M">Mineria</option>
							<option value=" ">Normal</option>
							</select></td><tr>
<td>AFP: </td><td><select id="afp" name="afp">
				  <option value="">Seleccione..</option>
				  <option value="HO">Horizonte</option>
        		  	  <option value="NV">Union Vida</option>
				  <option value="IN">Integra</option>
				  <option value="PR">Profuturo</option>
				  <option value="RI">Prima</option>
				  </select></td>
<td>
	<select id="ano_a" name="ano_a" >
		<option value="">..Seleccione</option>
		<?php
			for($i=2009;$i<=2015;$i++){ 
				
				if($i==date("Y")){ $s="SELECTED";}else{$s="";}
				echo '<option value='.$i.' '.$s.'>'.$i.'</option>';
			}
		?>
	</select>
	<select id="mes_a" name="mes_a">
		<option value="">..Seleccione</option>
		<?php
			for($i=1;$i<=12;$i++){ echo '<option value='.$i.'>'.$i.'</option>';}
		?>
	</select>
</td>
</table>
<hr/><hr/>
<input type="submit" value="Insertar" />
	<input type="button" value="Exportar" onclick="abrirventana();" />
	<select id="ano_afp" >
		<option value="">..Seleccione</option>
		<?php
			for($i=2009;$i<=2015;$i++){ 
				
				if($i==date("Y")){ $s="SELECTED";}else{$s="";}
				echo '<option value='.$i.' '.$s.'>'.$i.'</option>';
			}
		?>
	</select>
	<select id="mes_afp" >
		<option value="">..Seleccione</option>
		<?php
			for($i=1;$i<=12;$i++){ echo '<option value='.$i.'>'.$i.'</option>';}
		?>
	</select>
</center>

</form>
<?
	require "includes/dg/ex1-ajax.php";
	require "insert_planilla.php";
?>