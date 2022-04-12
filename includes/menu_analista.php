<ul id="menu" class="noicons">
				
				<li>_Procesos
					<ul class="noicons">
						<li onclick="buscar('includes/fil_asi_cta_res.php?')">Asignacion Cuentas por Resultados</li>
						<li><a href="index.php?gestion=1">Negociacion</a></li>
						<li onclick="buscar('includes/fil_dir_tel.php');" >Direcciones / Tel&eacute;fonos</li>
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
							</ul>
						</li>
						<li onclick="buscar('includes/fil_report.php?');">Foto de Cartera</li>
						<li onclick="buscar('includes/gest_report.php?');">Gestiones</li>
						<li onclick="buscar('includes/digi_gest_report.php?');">Digitacion</li>
						<li onclick="buscar('includes/fil_seg_cart.php?');">Segmentacion de Cartera</li>
						<li onclick="buscar('includes/fil_prodtv.php?');">Resumen Productividad</li>
						<!--<li onclick="buscar('est_user.php');">Usuarios en Linea</li>-->

					</ul>
				</li>

                <li>_Importar
					<ul class="noicons">
						<li onclick="buscar('importar.php?');">_Importar</li>
						<li onclick="buscar('importar_gc.php?');">_Importar Gestiones Call</li>
					</ul>
				</li>
                <li >
					<a href="logout.php?">_Salir</a>

				</li>
			</ul>
			<div style="float:right;position:absolute;top:8px; left:70%;margin;color:white;font-size:14px;" id="hora"><?php echo date("H:i:s");?></div>