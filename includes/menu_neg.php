	<!--<div style="float:right;position:absolute;top:8px; left:65%;margin;color:white;font-size:14px;" id="hora">
		<iframe src="http://192.168.50.6/gestion/functions/hora_a.php" width=100 height=22 style="color:white;border:0px;"></iframe>
	</div>-->
    <ul class="derecha">
        <li><a href="#" title="Products" class="loginArrow"><?php echo $nivel->fields['usuario']; ?></a></li>
        <li><a href="#" title="Products" class="loginArrow">
		<?php
        setlocale(LC_ALL,"","es_ES","esp");
		include 'includes/tras_fecha.php';
       // $fecha = utf8_encode(strftime("%A %d de %B del %Y"));
        echo $fecha;?></a></li>
    </ul>
    <ul class="NewNav">
        <li>
			<a href="index.php" title="Negociación">Negociación</a>
		</li>
		<?php if(!isset($_SESSION['campo'])){ ?>
					<!--	<li>
							<a href="http://192.168.50.16/o8/bin/barra.php?user=<?php echo $_SESSION['user_p'];?>&pass=<?php echo $_SESSION['pass_p'];?>" target="_blank">Predictivo</a> 
						</li>-->
					<?php }?>
	
        <li><a class="loginArrow" href="logout.php" title="eProject Login"><strong>Salir</strong></a></li>
        <li></li>
    </ul>

