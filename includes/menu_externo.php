<ul id="menu" class="noicons">
				
				<li>_Procesos
					<ul class="noicons">
						<li><a href="index.php?gestion=1">Negociacion</a></li>
					</ul>
				</li>

				<li>_Reportes
					<ul class="noicons">
						<li onclick="buscar('includes/gest_report.php?');">Gestiones</li>
						<li onclick="buscar('includes/fil_prodtv.php?');">Resumen Productividad</li>
					</ul>
				</li>
                <li >
					<a href="logout.php">_Salir</a>

				</li>
			</ul>
			<div style="float:right;position:absolute;top:8px; left:70%;margin;color:white;font-size:14px;" id="hora"><?php echo date("H:i:s");?></div>