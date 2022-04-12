<?php

?>
<!-- Prototype para extender funciones de JS -->


<div id="TabbedPanels1" class="TabbedPanels">
    <ul class="TabbedPanelsTabGroup">
        <li class="TabbedPanelsTab" tabindex="0">Clientes</li>
        <li class="TabbedPanelsTab" tabindex="1">Gestión</li>
        <li class="TabbedPanelsTab" tabindex="3">Tareas</li>
        <li class="TabbedPanelsTab" tabindex="4">Direcciones</li>
        <li class="TabbedPanelsTab" tabindex="5">Teléfonos</li>
        <li class="TabbedPanelsTab" tabindex="6">Contactos</li></ul>
    <div class="TabbedPanelsContentGroup"><div class="TabbedPanelsContent">
            <!-- Clientes -->
            <div id="areaList">
                <a href="javascript:imprimir()"><span class="impresion"></span></a>
                <!-- D A T O S  D E L  C L I E N T E -->
                <table width="100%" id="design4">
                    <caption>Datos del cliente</caption>
                    <tr class="noborde">
                        <td valign="top">
                            <table width="100%" id="design3">
                                <tr>
                                    <td width="20%" class="cellhead">Nombre:</td>
                                    <td width="10%">--------------</td>
                                    <td class="cellhead">Documento:</td>
                                    <td>-----------------</td>
                                    <td class="cellhead">Personeria:</td>
                                    <td>------------- </td>
                                    <td class="cellhead"></td>
                                    <td>-------------</td>
                                </tr>
                            </table>

                        </td>
                        <td valign="top">
                            <!--<table width="100%" id="design3">
                                <tr>
                                    <td class="cellhead">Clasificación:</td>
                                    <td>Ubicables</td>
                                </tr>
                                <tr>
                                    <td class="cellhead">Zona:</td>
                                    <td>SIN ZONA</td>
                                </tr>
                                <tr>
                                    <td class="cellhead">Número de Documento:</td>
                                    <td>D.N.I. : 80357910</td>
                                </tr>
                                <tr>
                                    <td class="cellhead">Observaciones:</td>
                                    <td></td>
                                </tr>
                            </table>-->

                        </td>
                    </tr>
                    <!--<tr class="noborde">
                        <td class="cellhead" colspan="2">
                            <form name="frmUbicabilidad">
                                <table width="100%" id="design3">
                                    <tr>
                                        <td class="cellhead">Horario de ubicabilidad:</td>
                                        <td><input type="radio" name="ubicabilidad" onchange="grabar_ubicabilidad(1);" /></td>
                                        <td>(M / M – 07:00 hrs -> 10:00 hrs)</td>
                                        <td><input type="radio" name="ubicabilidad" onchange="grabar_ubicabilidad(2);" /></td>
                                        <td>(M / T – 10:01 hrs -> 14:00 hrs)</td>
                                        <td><input type="radio" name="ubicabilidad" onchange="grabar_ubicabilidad(3);" /></td>
                                        <td>(T / T – 14:01 hrs -> 18:00 hrs)</td>
                                        <td><input type="radio" name="ubicabilidad" onchange="grabar_ubicabilidad(4);" /></td>
                                        <td>(T / N – 18:01 hrs -> 21:00 hrs)</td>
                                        <td><span id="span_ubicabilidad" style="color:#FF0000; font-weight:bold; visibility:hidden;">Grabando...</span></td>
                                    </tr>
                                </table>
                            </form>
                        </td>
                    </tr>-->
                </table>
            </div>
            <!-- D I R E C C I O N E S -->
            <div id="areaList" >
                <table width="60%" id="design4" align="left">
                    <form name="frmDireccion" method="post">
                        <caption>Direcciones</caption>
                        <thead>
                            <tr>
                                <!--<td valign="top">-->
                                <th>Dirección</th>
                                <th>Origen</th>
                                <th>Dpto</th>
                                <th>Provincia</th>
                                <th>Distrito</th>
                                <th>Plano</th>
                                <th>Cuadrante</th>
                                <th>Estado</th>
                                <th>Observaci&oacute;n</th>
                                <th colspan="2">Priorización<br />&nbsp;<sub>Dir.</sub> &nbsp;&nbsp; | &nbsp;&nbsp; <sub>Tel.</sub></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Aca va la direccion..</td>
                                <td>Aca va el origen..</td>
                                <td>Aca va el Dpto</td>
                                <td align="center">-</td>
                                <td align="center">-</td>
                                <td align="center">-</td>
                                <td align="center">-</td>
                                <td align="center">-</td>
                                <td align="center">-</td>
                                <td align="center"><input type="checkbox" id="ed936592" onclick="estado(this, 'd', '936592');" checked disabled /></td>
                                <td align="center"><input type="radio" name="dprioridad" id="d936592" onchange="priorizacion('pd', '778906', '936592');" checked /></td>
                                
                            </tr>
                            
                        </tbody>
                    </form>
                </table>
                <table width="40%" id="design4" align="left">
                    <form name="frmDireccion" method="post">
                        <caption>Teléfonos</caption>
                        <thead>
                            <tr>
                                <!--<td valign="top">-->
                                <th>N&uacute;mero</th>
                                <th>Origen</th>
                                <th>Estado</th>
                                <th>Priorizaci&oacute;n</th>
                                <th>Observaci&oacute;n</th>
                                
                                <th colspan="2">Priorización<br />&nbsp;<sub>Dir.</sub> &nbsp;&nbsp; | &nbsp;&nbsp; <sub>Tel.</sub></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>..</td>
                                <td>..</td>
                                <td>..</td>
                                <td align="center">-</td>
                                <td align="center">-</td>
                                
                                <td align="center"><input type="checkbox" id="ed936592" onclick="estado(this, 'd', '936592');" checked disabled /></td>
                                <td align="center"><input type="radio" name="dprioridad" id="d936592" onchange="priorizacion('pd', '778906', '936592');" checked /></td>

                            </tr>
                            </tbody>
                    </form>
                </table>
            </div><br />
            <!-- C O N T A C T O S -->
            <div id="areaList">
                <p> ..</p>
                <div style="overflow:auto">
                    <table width="100%" id="tabla_cuentas" class="tabla_listado">
                        <caption>Cuentas <a href="#" onclick="mostrar_ocultar(false);"><span id="mas_menos">(+)</span></a></caption>
                        <thead>
                            <tr><!-- nohighlight -->
                                <th>Cta1</th><th>Cta2</th><th>Cta3</th><th>Moneda</th><th>Capital total</th><th>Capital vencido</th><th class="oculto">Intereses</th><th class="oculto">Gastos</th><th class="oculto">Honorarios</th><th class="oculto">Penalidad</th><th>Total</th><th>Total vencido</th><th>Proveedor - Producto</th><th>Mora</th><th class="oculto">Cuotas crédito</th><th class="oculto">Cuotas pagadas</th><th class="oculto">Cuotas vencidas</th><th>Valor cuota</th><th>F. Vcto</th><th class="oculto">F. Ingreso</th><th class="oculto">Cartera</th><th>Grupo</th><th>Ciclo</th><th class="oculto">Auxiliar1</th><th class="oculto">Auxiliar2</th><th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="c80357910__006754555073">
                                <td>80357910</td>
                                <td>&nbsp;</td>
                                <td>006754555073</td>
                                <td>Soles</td>
                                <td class="numeros">358.00</td>
                                <td class="numeros">0.00</td>
                                <td class="numero_oculto">0.00</td>
                                <td class="numero_oculto">0.00</td>
                                <td class="numero_oculto">0.00</td>
                                <td class="numero_oculto">0.00</td>
                                <td class="numeros">358.00</td>
                                <td class="numeros">0.00</td>
                                <td>SCI - Consumo Vta2</td>
                                <td class="numeros">1,279</td>
                                <td class="numero_oculto">15</td>
                                <td class="numero_oculto">0</td>
                                <td class="numero_oculto">1</td>
                                <td class="numeros">179.00</td>
                                <td align="center">09/06/2007</td>
                                <td align="center" class="oculto">06/09/2010</td>
                                <td class="oculto">Castigada</td>
                                <td>L1</td>
                                <td class="numeros"></td>
                                <td class="oculto">NO</td>
                                <td class="oculto">CM2</td>
                                <td></td>
                            </tr>
                            <tr id="c80357910__006754555073">
                                <td>80357910</td>
                                <td>&nbsp;</td>
                                <td>006754555073</td>
                                <td>Soles</td>
                                <td class="numeros">358.00</td>
                                <td class="numeros">0.00</td>
                                <td class="numero_oculto">0.00</td>
                                <td class="numero_oculto">0.00</td>
                                <td class="numero_oculto">0.00</td>
                                <td class="numero_oculto">0.00</td>
                                <td class="numeros">358.00</td>
                                <td class="numeros">0.00</td>
                                <td>SCI - Consumo Vta2</td>
                                <td class="numeros">1,279</td>
                                <td class="numero_oculto">15</td>
                                <td class="numero_oculto">0</td>
                                <td class="numero_oculto">1</td>
                                <td class="numeros">179.00</td>
                                <td align="center">09/06/2007</td>
                                <td align="center" class="oculto">06/09/2010</td>
                                <td class="oculto">Castigada</td>
                                <td>L1</td>
                                <td class="numeros"></td>
                                <td class="oculto">NO</td>
                                <td class="oculto">CM2</td>
                                <td></td>
                            </tr>
                            <tr id="c80357910__006754555073">
                                <td>80357910</td>
                                <td>&nbsp;</td>
                                <td>006754555073</td>
                                <td>Soles</td>
                                <td class="numeros">358.00</td>
                                <td class="numeros">0.00</td>
                                <td class="numero_oculto">0.00</td>
                                <td class="numero_oculto">0.00</td>
                                <td class="numero_oculto">0.00</td>
                                <td class="numero_oculto">0.00</td>
                                <td class="numeros">358.00</td>
                                <td class="numeros">0.00</td>
                                <td>SCI - Consumo Vta2</td>
                                <td class="numeros">1,279</td>
                                <td class="numero_oculto">15</td>
                                <td class="numero_oculto">0</td>
                                <td class="numero_oculto">1</td>
                                <td class="numeros">179.00</td>
                                <td align="center">09/06/2007</td>
                                <td align="center" class="oculto">06/09/2010</td>
                                <td class="oculto">Castigada</td>
                                <td>L1</td>
                                <td class="numeros"></td>
                                <td class="oculto">NO</td>
                                <td class="oculto">CM2</td>
                                <td></td>
                            </tr>
                            <tr id="c80357910__006754555073">
                                <td>80357910</td>
                                <td>&nbsp;</td>
                                <td>006754555073</td>
                                <td>Soles</td>
                                <td class="numeros">358.00</td>
                                <td class="numeros">0.00</td>
                                <td class="numero_oculto">0.00</td>
                                <td class="numero_oculto">0.00</td>
                                <td class="numero_oculto">0.00</td>
                                <td class="numero_oculto">0.00</td>
                                <td class="numeros">358.00</td>
                                <td class="numeros">0.00</td>
                                <td>SCI - Consumo Vta2</td>
                                <td class="numeros">1,279</td>
                                <td class="numero_oculto">15</td>
                                <td class="numero_oculto">0</td>
                                <td class="numero_oculto">1</td>
                                <td class="numeros">179.00</td>
                                <td align="center">09/06/2007</td>
                                <td align="center" class="oculto">06/09/2010</td>
                                <td class="oculto">Castigada</td>
                                <td>L1</td>
                                <td class="numeros"></td>
                                <td class="oculto">NO</td>
                                <td class="oculto">CM2</td>
                                <td></td>
                            </tr>
                            <tr id="c80357910__006754555073">
                                <td>80357910</td>
                                <td>&nbsp;</td>
                                <td>006754555073</td>
                                <td>Soles</td>
                                <td class="numeros">358.00</td>
                                <td class="numeros">0.00</td>
                                <td class="numero_oculto">0.00</td>
                                <td class="numero_oculto">0.00</td>
                                <td class="numero_oculto">0.00</td>
                                <td class="numero_oculto">0.00</td>
                                <td class="numeros">358.00</td>
                                <td class="numeros">0.00</td>
                                <td>SCI - Consumo Vta2</td>
                                <td class="numeros">1,279</td>
                                <td class="numero_oculto">15</td>
                                <td class="numero_oculto">0</td>
                                <td class="numero_oculto">1</td>
                                <td class="numeros">179.00</td>
                                <td align="center">09/06/2007</td>
                                <td align="center" class="oculto">06/09/2010</td>
                                <td class="oculto">Castigada</td>
                                <td>L1</td>
                                <td class="numeros"></td>
                                <td class="oculto">NO</td>
                                <td class="oculto">CM2</td>
                                <td></td>
                            </tr>
                            <tr id="c80357910__006754555073">
                                <td>80357910</td>
                                <td>&nbsp;</td>
                                <td>006754555073</td>
                                <td>Soles</td>
                                <td class="numeros">358.00</td>
                                <td class="numeros">0.00</td>
                                <td class="numero_oculto">0.00</td>
                                <td class="numero_oculto">0.00</td>
                                <td class="numero_oculto">0.00</td>
                                <td class="numero_oculto">0.00</td>
                                <td class="numeros">358.00</td>
                                <td class="numeros">0.00</td>
                                <td>SCI - Consumo Vta2</td>
                                <td class="numeros">1,279</td>
                                <td class="numero_oculto">15</td>
                                <td class="numero_oculto">0</td>
                                <td class="numero_oculto">1</td>
                                <td class="numeros">179.00</td>
                                <td align="center">09/06/2007</td>
                                <td align="center" class="oculto">06/09/2010</td>
                                <td class="oculto">Castigada</td>
                                <td>L1</td>
                                <td class="numeros"></td>
                                <td class="oculto">NO</td>
                                <td class="oculto">CM2</td>
                                <td></td>
                            </tr>
                            <!-- cuentas -->
                        </tbody>
                    </table>
                </div>
                <table width="100%" id="tabla_cuentas_detalle" class="no-print">
                    <caption>Detalle de la cuenta</caption>
                    <tr>
                        <td><b>No hay detalles de cuenta</b></td>
                    </tr>
                </table><!-- GESTIONES -->
                <table width="100%" id="design4">
                    <tr>
                        
                    </tr>
                </table>
                <span id="span_gestiones_call" style="visibility:visible;position:relative;"></span>
                <span id="span_gestiones_campo" style="visibility:hidden;position:absolute;"></span>
                <!-- PAGOS -->
                <table width="100%" id="tabla_pagos">
                    <caption>Pagos</caption>
                    <tr>
                        <td><b>No hay pagos</b></td>
                    </tr>
                    </tbody>
                </table>
            </div><br />
            <div class="no-print">
                <input class="btn1" type="button" value="Siguiente &raquo;" onclick="self.location.href='index.php';" />&nbsp;&nbsp;&nbsp;
                <input class="btn1" type="button" value="Regresar" onclick="self.location.href='index.php';" />&nbsp;&nbsp;&nbsp;<br /><br /></div>
        </div>
        <!-- Fin Clientes -->
        <div class="TabbedPanelsContent">
            <!-- Gestion -->
            <div id="areaForm">
                <div class="zpFormContent">
                    <table width="100%">
                        <tr>
                            <td><!-- Seccion de gestión -->
                                <fieldset><legend>Proceso de Gestión</legend>
                                    <form name="frmDatos" id="userForm" class="zpForm" method="post" style="text-align:left">
                                        <label for="neggestion" class="zpFormLabel">Negociador:</label>
                                        <input type="text" name="neggestion" class="zpFormNotRequired" value="Terry Ruiz Otilia Soledad" size="45" disabled /><br />
                                        <label for="cuenta2" class="zpFormLabel">Cuenta:</label>
                                        <select id="cuenta2" name="cuenta2[]" class="zpFormRequired" multiple size="1">
                                            <option value="28383002" selected>80357910 -  - 006754555073 - Consumo Vta2 - S/. 0.00</option>
                                        </select><br />
                                        <label for="cliente" class="zpFormLabel">Cliente:</label>
                                        <input name="clientes" type="text" class="zpFormNotRequired" id="clientes" value="Valladares Cosme, Ofelia Aide " size="40" disabled />
                                        <input name="cliente" type="hidden" id="cliente" value="778906"/><br />
                                        <label for="fechagestion" class="zpFormLabel">Fecha Gestión:</label>
                                        <input type="text" class="zpFormRequired zpFormDate" name="fechagestion" id="fechagestion" value="04/01/2011" size="11" maxlength="10" disabled /><br />
                                        <label for="resultado" class="zpFormLabel">Resultado Gestión:</label>
                                        <select name="resultado" class="zpFormRequired" onchange="activarcompromiso();">
                                            <option value="">Seleccione...</option>
                                        </select><br />
                                        <div id="div_justificacion" style="visibility:hidden;position:absolute;">
                                            <label for="justificacion_a" class="zpFormLabel">Justificaci&oacute;n:</label>
                                            <select name="justificacion_a" class="zpFormRequired">

                                            </select><br />
                                        </div>
                                        <div id="resultados" style="visibility:hidden">
                                            <label for="fechacompromiso" class="zpFormLabel">F. Compromiso:</label>
                                                <input type="text" class="zpFormRequired zpFormDate" name="fechacompromiso" id="fechacompromiso" value="" size="11" maxlength="10" onchange="cambio_fecha_compromiso();" />
                                                <input type="button" id="bcalendario1" value=" ... " />
                                                                                    
                                            <label for="moneda" class="zpFormLabel">Moneda:</label>
                                                <input type="text" size="11" class="zpFormNotRequired" name="moneda" value="Soles" disabled /><br />
                                            <label for="importecompromiso" class="zpFormLabel">Imp. Compromiso:</label>
                                                <input type="text" class="zpFormRequired zpFormFloat" name="importecompromiso" id="importecompromiso" value="0.00" onchange="validar_importe_compromiso();" size="10" maxlength="15" style="text-align:right" /><br />
                                                <!--<br /> -->
                                        </div>
                                        <label for="indicador" class="zpFormLabel">Indicador:</label>
                                        <select name="indicador" class="zpFormRequired" onchange="cambio_indicador();">
                                            <option label="OC - Llamada de salida" value="1">Seleccione...</option>
                                        </select><br />
                                        <div id="div_contacto">
                                            <label for="telefono_gestion" class="zpFormLabel">Telef. Gestionado:</label>
                                            <select name="telefono_gestion" class="zpFormRequired" size="2">
                                                <option value="">Seleccione...</option>
                                                
                                            </select><br />
                                            <label for="tipo_contacto" class="zpFormLabel">Tipo de Contacto:</label>
                                            <select name="tipo_contacto" class="zpFormRequired" onchange="validar_tipo_contacto();">
                                                <option value="">Seleccione...</option>

                                            </select><br />
                                        </div>
                                        <label for="observaciones" class="zpFormLabel">Observaci&oacute;n:</label>
                                                                                                                                                 <textarea name="observaciones" class="zpFormNotRequired" cols="50" rows="4"></textarea><input type="hidden" name="fechasistema" value="" /><br />
                                        <label for="resultado" class="zpFormLabel">Resultado Gestión:</label>
                                        <select name="resultado" class="zpFormRequired" onchange="activarcompromiso();">
                                            <option value="">Seleccione...</option>
                                            <option value="5010-57-N">5010 - No Contacto - Busqueda Externa</option>
                                            <option value="5011-58-N">5011 - No Contacto - Busqueda Avanzada</option>
                                            <option value="5015-63-P">5015 - Pendiente - Documento en Transito</option>
                                            <option value="5017-69-P">5017 - Pendiente - Envio de Carta</option>
                                        </select><br />
                                        <div id="div_justificacion" style="visibility:hidden;position:absolute;">
                                            <label for="justificacion_a" class="zpFormLabel">Justificaci&oacute;n:</label>
                                            <select name="justificacion_a" class="zpFormRequired"></select><br />
                                        </div>
                                        <div id="resultados" style="visibility:hidden">
                                            <label for="fechacompromiso" class="zpFormLabel">F. Compromiso:</label>
                                            <input type="text" class="zpFormRequired zpFormDate" name="fechacompromiso" id="fechacompromiso" value="" size="11" maxlength="10" onchange="cambio_fecha_compromiso();" />
                                            <input type="button" id="bcalendario1" value=" ... " />
                                            <label for="horatarea" class="zpFormLabel">Hora:</label>
                                            <input type="text" name="horatarea" value="00:00" size="6" maxlength="5" class='zpFormRequired zpFormHour zpFormMask="00:00"' /> (HH:mm)<br />
                                            <label for="comentariotarea" class="zpFormLabel">Comentarios:</label>
                                            <input type="text" name="comentariotarea" class="zpFormNotRequired" size="75" maxlength="100" value=""/><br />

                                    </div>
                                        <div class="zpFormButtons">
                                            <input type="hidden" name="acc" value="1" />
                                            <input type="hidden" name="codigo_form" value="20110104103651" />
                                            <input type="hidden" name="negid1" value="55" />
                                            <input type="hidden" name="tarea_incumplida" value="no" />
                                            <input type="submit" value="Aceptar" />
                                        </div>
                                    </form>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <!-- /areaForm -->
        </div> <!-- Fin Gestion -->
        <div class="TabbedPanelsContent">
            <!-- Tareas -->
            <div id="areaForm">
                <fieldset><legend>Tareas:</legend>
                    <form name="frmDatos4" id="userForm4" class="zpForm" method="post" style="text-align:left">
                        <label for="negtarea" class="zpFormLabel">Negociador:</label>
                        <input type="text" name="negtarea" class="zpFormNotRequired" value="Terry Ruiz Otilia Soledad" size="45" disabled /><br />
                        <label for="fechatarea" class="zpFormLabel">Fecha:</label>
                        <input type="text" class="zpFormRequired zpFormDate" name="fechatarea" id="fechatarea" value="" size="11" maxlength="10" />
                        <input type="button" id="bcalendario3" value=" ... " />
                                                                <br /><label for="horatarea" class="zpFormLabel">Hora:</label><input type="text" name="horatarea" value="" size="6" maxlength="5" class='zpFormRequired zpFormHour zpFormMask="00:00"' /> (HH:mm)<br /><label for="comentariotarea" class="zpFormLabel">Comentarios:</label><input type="text" name="comentariotarea" class="zpFormNotRequired" size="75" maxlength="100" value=""/><br /><br /><div class="zpFormButtons"><input type="hidden" name="codigo_form" value="20110104103651" /><input type="hidden" name="cliid" value="778906" /><input type="hidden" name="negid1" value="55" /><input type="hidden" name="acc1" value="tar" /><input type="submit" value="Grabar" /></div></form></fieldset></div></div> <!-- Fin Tareas --><div class="TabbedPanelsContent"> <!-- Direcciones --><div id="areaForm"><fieldset><legend>Nueva Dirección</legend><form name="frmDatos2" id="userForm2" class="zpForm" method="post" style="text-align:left"><label for="negdireccion" class="zpFormLabel">Negociador:</label><input type="text" name="negdireccion" class="zpFormNotRequired" value="Terry Ruiz Otilia Soledad" size="45" disabled /><br /><label for="origen" class="zpFormLabel">Origen:</label><select name="origen" class="zpFormRequired" id="origen" ><option value="">Seleccione...</option><option label="Banco" value="8">Banco</option>

<option label="Casa" value="1">Casa</option>
<option label="Familiares" value="5">Familiares</option>
<option label="Oficina" value="3">Oficina</option>
<option label="Otros" value="4">Otros</option>
</select><br />
<label for="priorizacion" class="zpFormLabel">Priorización:</label>
<select name="priorizacion" class="zpFormRequired" onchange="ver_priorizacion('direccion');">
    <option value="">Seleccione...</option>
    <option label="Primario" value="1">Primario</option>
    <option label="Secundario" value="2">Secundario</option>
</select><br />
<label for="tipo" class="zpFormLabel">Tipo:</label>
<select name="tipo" class="zpFormRequired" >
    <option value="">Seleccione...</option>
    <option label="Activo" value="1">Activo</option>
    <option label="Inactivo" value="2">Inactivo</option>
</select><br />
<label for="departamento" class="zpFormLabel">Departamento:</label>
<select name="departamento" class="zpFormRequired" onchange="buscar_provincias('frmDatos2');" >
    <option value="">Seleccione...</option>
</select><br />
<label for="provincia" class="zpFormLabel">Provincia:</label>
<select name="provincia" class="zpFormRequired" onchange="buscar_distrito('frmDatos2');">
    <option value="">Seleccione...</option>
</select><br />
<script>//buscar_provincias();</script>
<label for="distrito" class="zpFormLabel">Distrito:</label>
<select name="distrito" class="zpFormRequired" onchange="buscar_cuadrante('frmDatos2');">
    <option value="">Seleccione...</option>
</select><br />
<label for="direccion" class="zpFormLabel">Direcci&oacute;n:</label>
<input type="text" name="direccion" class="zpFormRequired" value="" size="75" maxlength="255" />
<input type="button" value="Buscar cuadrante..." name="busqueda_cuadrante" onclick="cuadrantes();" disabled /><br />
<label for="cuadrante" class="zpFormLabel">Cuadrante:</label>
<select name="cuadrante" class="zpFormRequired" >
    <option value="">Seleccione...</option>
</select><br />
<label for="observacion" class="zpFormLabel">Observaci&oacute;n:</label>
<input type="text" name="observacion" class="zpFormNotRequired" value="" size="75" maxlength="100" /><br />
<div class="zpFormButtons">
    <input type="hidden" name="codigo_form" value="20110104103651" />
    <input type="hidden" name="cliid" value="778906" />
    <input type="hidden" name="acc1" value="dir" />
    <input type="submit" value="Aceptar" />
</div>
                                                                            </form>
                                                                        </fieldset></div></div> <!-- Fin Direcciones --><div class="TabbedPanelsContent"> <!-- Telefonos --><div id="areaForm"><fieldset><legend>Nuevo Tel&eacute;fono:</legend><form name="frmDatos3" id="userForm3" class="zpForm" method="post" style="text-align:left"><label for="negtelefono" class="zpFormLabel">Negociador:</label><input type="text" name="negtelefono" class="zpFormNotRequired" value="Terry Ruiz Otilia Soledad" size="45" disabled /><br /><label for="numerotelefono" class="zpFormLabel" >N&deg; tel&eacute;fono:</label><input type="text" class="zpFormRequired" name="numerotelefono" value="" size="15" maxlength="15" autocomplete="off" onchange="buscar_telefono();" /> <span id="span_mensaje_telefono" style="color:#FF0000;visibility:hidden;"></span><br /><label for="origen" class="zpFormLabel" >Origen:</label><select name="origentelefono" class="zpFormRequired" id="origentelefono"><option value="">Seleccione...</option><option label="Banco" value="8">Banco</option>

<option label="Casa" value="1">Casa</option>
<option label="Movil" value="2">Movil</option>
<option label="Oficina" value="3">Oficina</option>
<option label="Otros" value="4">Otros</option>
</select><br /><label for="priorizacion" class="zpFormLabel">Priorización:</label><select name="priorizacion" class="zpFormRequired" onchange="ver_priorizacion('telefono');"><option value="">Seleccione...</option><option label="Primario" value="1">Primario</option>
<option label="Secundario" value="2">Secundario</option>
</select><br />
<label for="tipo" class="zpFormLabel" >Tipo:</label>
<select name="tipotelefono" class="zpFormRequired" id="tipotelefono">
    <option value="">Seleccione...</option>
    <option label="Activo " value="1">Activo </option>
    <option label="Inactivo" value="2">Inactivo</option>
</select><br />
<label for="direcciontelefono" class="zpFormLabel" >Dirección:</label>
<select name="direcciontelefono" class="zpFormNotRequired">
    <option value="NULL">Sin Dirección</option>
    <option value="936592">Jr M.Melgar 168 Urb Collique Zna 1</option>
</select><br />
<label for="observaciontelefono" class="zpFormLabel" >Observaci&oacute;n:</label>
<input type="text" name="observaciontelefono" class="zpFormNotRequired" size="75" maxlength="100" value="" /><br />
<div class="zpFormButtons">
    <input type="hidden" name="codigo_form" value="20110104103651" />
    <input type="hidden" name="cliid" value="778906" />
    <input type="hidden" name="acc1" value="tel" />
    <input type="submit" value="Aceptar" />
</div>
            </form>
        </fieldset></div></div> <!-- Fin Telefonos --><div class="TabbedPanelsContent"> <!-- Contactos --><div id="areaForm"><fieldset><legend>Nuevo Contacto:</legend><form name="frmDatos5" id="userForm5" class="zpForm" method="post" style="text-align:left"><label for="negdireccion" class="zpFormLabel">Negociador:</label><input type="text" name="negdireccion" class="zpFormNotRequired" value="Terry Ruiz Otilia Soledad" size="45" disabled /><br /><label for="apellidopaterno" class="zpFormLabel">Ap. Paterno:</label><input type="text" class="zpFormRequired" name="apellidopaterno" value="" size="15" maxlength="15" /><br /><label for="apellidomaterno" class="zpFormLabel">Ap. Materno:</label><input type="text" class="zpFormNotRequired" name="apellidomaterno" value="" size="15" maxlength="15" /><br /><label for="nombres" class="zpFormLabel">Nombres:</label><input type="text" class="zpFormRequired" name="nombres" value="" size="20" maxlength="20" /><br /><label for="email" class="zpFormLabel">Email:</label><input type="text" class="zpFormRequired" name="email" value="" size="50" maxlength="50" /><br /><table><tr><td><label for="telefono" class="zpFormLabel">Teléfono:</label><input type="text" class="zpFormNotRequired zpFormInt" name="telefono" value="" size="15" maxlength="15" /></td><td><label for="anexo" class="zpFormLabel">Anexo:</label><input type="text" class="zpFormNotRequired zpFormInt" name="anexo" value="" size="6" maxlength="6" /></td></tr></table><br /><label for="tipodoc" class="zpFormLabel">Tipo Documento:</label><select name="tipodoc" class="zpFormRequired" onchange="activar();"><option value="">Seleccione...</option><option label="D.N.I. " value="1">D.N.I. </option>

<option label="R.U.C." value="2">R.U.C.</option>
<option label="C.I.P." value="3">C.I.P.</option>
<option label="Otros" value="4">Otros</option>
</select><br /><label for="dni" class="zpFormLabel">N° Documento:</label><input type="text" class="zpFormNotRequired" name="dni" value="" onKeydown="activar2();" /><br /><label for="parentesco" class="zpFormLabel">Parentesco:</label><select name="parentesco" class="zpFormRequired"><option value="">Seleccione...</option><option label="Amistad" value="2">Amistad</option>
<option label="Aval" value="4">Aval</option>
<option label="Conyuge" value="8">Conyuge</option>
<option label="Familiar" value="1">Familiar</option>

<option label="Laboral" value="3">Laboral</option>
<option label="Nadie" value="9">Nadie</option>
<option label="Otros" value="5">Otros</option>
<option label="Titular" value="6">Titular</option>
</select><br /><label for="cargo" class="zpFormLabel">Cargo:</label><select name="cargo" class="zpFormRequired"><option value="">Seleccione...</option><option label="Contabilidad" value="3">Contabilidad</option>
<option label="Gerencia" value="2">Gerencia</option>
<option label="Ninguno" value="1">Ninguno</option>
<option label="Pago a Proveedores" value="6">Pago a Proveedores</option>

<option label="Recepcion" value="5">Recepcion</option>
<option label="Tesoreria" value="4">Tesoreria</option>
</select><br /><label for="direccion" class="zpFormLabel">Dirección:</label><textarea name="direccion" class="zpFormRequired" cols="25" rows="6"></textarea><br /><label for="observacion" class="zpFormLabel">Comentarios:</label><textarea name="observacion" class="zpFormNotRequired" cols="25" rows="3"></textarea><br /><div class="zpFormButtons"><input type="hidden" name="codigo_form" value="20110104103651" /><input type="hidden" name="cliid" value="778906" /><input type="hidden" name="acc1" value="con" /><input type="submit" value="Aceptar" /></div></form></fieldset></div></div> <!-- Fin Contactos --></div></div>
<script type="text/javascript">
	var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1", {defaultTab:0});
</script>
</div>
