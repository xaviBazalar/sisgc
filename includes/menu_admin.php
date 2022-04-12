<ul id="menu" class="noicons">
				<li>_Mantenimientos 
					<ul class="noicons">
						<li id="item-index" onclick="mostrar('user_new=2&pag=1');">_Usuarios</li>
						<li onclick="mostrar('r_usuarios')">Reinicio de Claves</li>
						<li id="item-index" onclick="buscar('includes/fil_pre_user_x_cartera.php?');">_Usuarios(Multiples Carteras)</li>
						<li onclick="m4('pe_o&pag=1');">Periodos</li>
						<li>Proveedores
							<ul class="noicons">
								<li onclick="m3('provee&pag=1');">Proveedor</li>
                                <li onclick="m3('cart&pag=1');">Cartera</li>
								<li onclick="m3('tip_cart&pag=1');">Tipo Cartera</li>
								<li onclick="m3('pro&pag=1');">Productos</li>
								<li></li>
								<li onclick="m3('seg&pag=1');">Segmento</li>
							</ul>
						</li>

						<li>Parametros
							<ul class="noicons">
								<li onclick="m2('doc&pag=1');">Documentos</li>
								<li onclick="m2('pers&pag=1');">Personeria</li>
                                <li onclick="m2('nivel&pag=1');">Niveles</li>
                                <li onclick="m2('or_dir&pag=1');">Origen de Direcci&oacute;n</li>
                                <li onclick="m2('or_tel&pag=1');">Origen de Tel&eacute;fono</li>
								<li onclick="m2('or_email&pag=1');">Origen de Email</li>
                                <li onclick="m2('parent&pag=1');">Parentesco</li>
                                <li onclick="m2('conta&pag=1');">Contactabilidad</li>
								<li onclick="m2('t_conta&pag=1');">Tipo Contactabilidad</li>
                                <li onclick="m2('ubic&pag=1');">Ubicabilidad</li>
                                <li onclick="m2('money&pag=1');">Monedas</li>
                                <li></li>
								<li onclick="m2('tpredio&pag=1');">Tipo Predio</li>
								<li onclick="m2('mpredio&pag=1');">Material Predio</li>
								<li onclick="m2('npisos&pag=1');">Nro Pisos</li>
								<li onclick="m2('cpared&pag=1');">Color Pared</li>
								<li></li>
                                <li onclick="m2('actvd&pag=1');">Actividades</li>
								<li onclick="m2('fuente&pag=1');">Fuentes</li>
								<li onclick="m2('valid&pag=1');">Validaciones</li>
							</ul>
						</li>

                        <li>Gesti&oacute;n
							<ul class="noicons">
                                <li onclick="m5('grupo&pag=1');">Grupos</li>
                                <li></li>
								<li onclick="m5('result&pag=1');">Resultados</li>
								<li onclick="m5('just&pag=1');">Justificaciones</li>
	                            
                                <li></li>
                                <li onclick="m5('result_c&pag=1');">Resultados por Cartera</li>
								<li onclick="m5('conta_c&pag=1');">Contactabilidad por Cartera</li>
								<li onclick="m5('just_c&pag=1');">Justificaciones por Cartera</li>
								<li onclick="m5('actv_c&pag=1');">Actividades por Cartera</li>


							</ul>
						</li>
                                                <li>Zonificaci&oacute;n
							<ul class="noicons">
                                <li onclick="location.href='index.php?tipo=plano&parametros=ubig&&pag=1&&id_dpto='">Ubigeos</li>

                                <!--<li onclick="location.href='index.php?tipo=plano&parametros=planos&&pag=1&&id_dpto='"> Planos</li>
                                <li onclick="location.href='index.php?tipo=plano&parametros=cuadr&&pag=1&&id_dpto='">Cuadrantes</li>-->
							</ul>
						</li>
						
					</ul>
				</li>
				<li>_Procesos
					<ul class="noicons">
						<li>Asignacion Cuentas
							<ul class="noicons">
								<li onclick="buscar('includes/fil_asi_cta_res.php?')"> x Cartera</li>
								<li onclick="buscar('includes/fil_asi_cta_gs.php?')"> x Resultado Gestion</li>
								<li onclick="buscar('includes/fil_asi_cta_in.php?')"> x Indicadores</li>
							</ul>
						</li>
						<li>Asignacion Cuentas TV
							<ul class="noicons">
								<li onclick="buscar('includes/fil_asi_cta_tv.php?')"> x Campa&ntilde;a</li>
								<li onclick="buscar('includes/fil_asi_cta_gs_tv.php?')"> x Resultado Gestion</li>
							</ul>
						</li>
						<li><a href="index.php?gestion=1">Negociacion</a></li>
						<li onclick="buscar('includes/fil_dir_tel.php?');" >Direcciones / Tel&eacute;fonos</li>
					</ul>
				</li>

				<li>_Reportes
					<ul class="noicons">
						<li onclick="buscar('includes/fil_rep_gg.php?');">Reporte _______</li>
						<li onclick="buscar('includes/fil_rep_cobertura.php?');">Reporte Cobertura</li>
						<li>BANCO CENCOSUD
							<ul class="noicons">
								<li onclick="buscar('includes/fil_bco_cencosud.php?');">Interfaces</li>
								<li onclick="buscar('includes/fil_bco_cencosud_cont.php?');">Reporte Contactabilidad</li>
								<li onclick="buscar('includes/fil_bco_cencosud_riesgos.php?');">Riesgos</li>
								<li onclick="buscar('includes/fil_bco_cencosud_trama.php?');">Trama</li>

								
							</ul>
						</li>
						<li>TV
							<ul class="noicons">
								<li onclick="buscar('includes/fil_bco_trama_tv.php?');">Trama</li>
							</ul>
						</li>
						<li onclick="buscar('includes/fil_report.php?');">Asignacion  Cartera</li>
						<li onclick="buscar('includes/gest_report.php?');">Gestiones</li>
						<li onclick="buscar('includes/digi_gest_report.php?');">Digitación</li>
						<li onclick="buscar('includes/fil_prodtv.php?');">Resumen Productividad</li>
						<!--<li onclick="buscar('est_user.php');">Usuarios en Linea</li>-->

					</ul>
				</li>

                <li>_Importar
					<ul class="noicons">
						<li onclick="buscar('importar.php?');">_Importar</li>
						<li onclick="buscar('importar_tv.php?');">_Importar TV</li>
						<!--<li onclick="buscar('importar_gc.php?');">_Importar Gestiones Call</li>-->
					</ul>
				</li>
                <li >
					<a href="logout.php">_Salir</a>

				</li>
			</ul>
			<div style="float:right;position:absolute;top:8px; left:70%;margin;color:white;font-size:14px;" id="hora"><?php echo date("H:i:s");?></div>