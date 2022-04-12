      <?php $nro_map=$_GET['mapa'] ?>
	  <html>
            <head>
                 <title>Gestion -  Planos</title>
           
                 <!-- link to magiczoom.css file -->
                <link href="scripts/magiczoom.css" rel="stylesheet" type="text/css" media="screen"/>
                 <!-- link to magiczoom.js file -->
                 <script src="scripts/magiczoom.js" type="text/javascript"></script>
              
            </head>
            <body>
                 <!-- define Magic Zoom -->
              <a href="planos/PLANO_<?php echo $nro_map; ?>.png" rel="zoom-fade:true; smoothing-speed:16;zoom-width:670px;zoom-height:500px;" title="Gestion-Plano" class="MagicZoom"><img src="planos/pre_<?php echo $nro_map; ?>.jpg"/></a>
			</body>

      </html>

